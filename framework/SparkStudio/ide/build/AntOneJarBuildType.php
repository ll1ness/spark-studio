<?php
namespace ide\build;

use ide\forms\BuildProgressForm;
use ide\forms\BuildSuccessForm;
use ide\Ide;
use ide\Logger;
use ide\project\behaviours\BundleProjectBehaviour;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\behaviours\RunBuildProjectBehaviour;
use ide\project\Project;
use ide\project\ProjectFile;
use ide\systems\ProjectSystem;
use ide\utils\FileUtils;
use php\compress\ZipFile;
use php\gui\UXApplication;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\Process;
use php\lang\Thread;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;
use php\util\Regex;

class AntOneJarBuildType extends AbstractBuildType
{
    /** @var Thread|null */
    protected static $buildThread;
    /**
     * @return string
     */
    function getName()
    {
        return "JAR Приложение";
    }

    /**
     * @return string
     */
    function getDescription()
    {
        return 'Кроссплатформенное JAR приложение для Linux/Win/MacOS (требует Oracle JRE 1.8+)';
    }

    /**
     * @return mixed
     */
    function getIcon()
    {
        return 'icons/jarFile32.png';
    }

    /**
     * @param Project $project
     *
     * @return string
     */
    function getBuildPath(Project $project)
    {
        return $project->getRootDir() . '/build/dist';
    }

    public static function makeAntBuildFile(Project $project, array $config)
    {
        $project->copyModuleFiles($project->getRootDir() . "/build/dist/lib");

        $content = FileUtils::get('res://ide/build/ant/buildDist.xml');
        $content = str::replace($content, '#NAME#', $project->getName());
        $content = str::replace($content, '#JRE_DIR#', Ide::get()->getJrePath());
        $content = str::replace($content, '#BASE_DIR#', $project->getRootDir());

        $jarContent = '';

        $classPaths = [$project->getSrcGeneratedDirectory(), $project->getSrcDirectory()];

        if ($bundleBehaviour = BundleProjectBehaviour::get()) {
            $classPaths = $bundleBehaviour->getSourceDirectories();
        }

        foreach ($classPaths as $src) {
            $excludes = ".debug/** **/*.source **/*.sourcemap **/*.axml";

            if ($php = PhpProjectBehaviour::get()) {
                if ($php->isByteCodeEnabled()) {
                    $excludes .= " **/*.php";
                }
            }

            $jarContent .= "\t<fileset dir='$src' excludes='$excludes' erroronmissingdir='false'/>\n";
        }

        $content = str::replace($content, '#JAR_CONTENT#', $jarContent);
        $content = str::replace($content, '#DIST_CONTENT#', '');
        $content = str::replace($content, '#LAUNCH4J_DIR#', Ide::get()->getLaunch4JPath());

        if ($config['oneJar']) {
            $content = str::replace($content, '#L4J_JAR_FILE#', '${dist}/' . $project->getName() . '.jar');
            $content = str::replace($content, '#L4J_DONT_WRAP_JAR#', 'false');
        } else {
            $content = str::replace($content, '#L4J_JAR_FILE#', '');
            $content = str::replace($content, '#L4J_DONT_WRAP_JAR#', 'true');
        }

        if ($config['jre']) {
            $content = str::replace($content, '#L4J_JRE_PATH#', 'jre');
        } else {
            $content = str::replace($content, '#L4J_JRE_PATH#', '');
        }

        if ($config['exeIcoPath']) {
            $icoFile = File::of(Ide::get()->getOpenedProject()->getRootDir() . "/" . $config['exeIcoPath']);

            if (!$icoFile->isFile()) {
                $icoFile = File::of($config['exeIcoPath']);
            }

            if ($icoFile->isFile()) {
                $tmpIconFile = File::createTemp(str::uuid(), '.ico');
                $tmpIconFile->deleteOnExit();

                fs::copy($icoFile, $tmpIconFile);

                $content = str::replace($content, '#L4J_ICON_FILE#', $tmpIconFile);
            } else {
                $content = str::replace($content, 'icon="#L4J_ICON_FILE#"', '');
            }
        } else {
            $content = str::replace($content, 'icon="#L4J_ICON_FILE#"', '');
        }

        if (!$config['l4j']) {
            $content = Regex::of('\\<launch4j\\>.*\\<\\/launch4j\\>', 's')->with($content)->replaceGroup(0, '');
        }


        $extList = '';
        $oneJarContent = [];

        $addedModuleNames = [];

        foreach ($project->getModules() as $module) {
            if ($module->getType() == 'jarfile') {
                $name = fs::name($module->getId());

                if ($addedModuleNames[$name]) {
                    continue;
                }

                $addedModuleNames[$name] = 1;

                if ($php = PhpProjectBehaviour::get()) {
                    $excl = $php->isByteCodeEnabled() ? '**/*.php' : '';
                } else {
                    $excl = '';
                }

                $oneJarContent[] = "<zipfileset src='\${dist}/lib/{$name}' excludes='JPHP-INF/sdk/** $excl' />";

                try {
                    $zipFile = new ZipFile($module->getId());

                    if ($zipFile->has('META-INF/services/php.runtime.ext.support.Extension')) {
                        $zipFile->read('META-INF/services/php.runtime.ext.support.Extension', function ($stat, Stream $stream) use (&$extList) {
                            $extList .= "$stream" . "\n\n";
                        });
                    } else {
                        Logger::info("Skip extensions list for module {$module->getId()}");
                    }

                } catch (IOException $e) {
                    Logger::warn("Unable to read zip data from {$module->getId()}, {$e->getMessage()}");
                }
            }
        }

        $content = str::replace($content, '#ONE_JAR_CONTENT#', str::join($oneJarContent, " "));

        FileUtils::put($project->getRootDir() . "/build.xml", $content);
        FileUtils::put($project->getRootDir() . '/build/dist/gen/META-INF/services/php.runtime.ext.support.Extension', $extList);
    }

    /**
     * @param Project $project
     *
     * @param bool $finished
     *
     * @return mixed
     */
    function onExecute(Project $project, $finished = true)
    {
        FileUtils::deleteDirectory($this->getBuildPath($project));
        
        // Pre-load BuildProgressForm class to avoid class loader issues in background thread
        class_exists('ide\\forms\\BuildProgressForm');
        
        $dialog = new BuildProgressForm();
        $dialog->show();

        $dialog->setStopProcedure(function () {
            ProjectSystem::stopCompile();
            return true;
        });

        $onExitProcess = function ($exitValue) use ($project, $dialog, $finished) {
            Logger::info("Finish executing: exitValue = $exitValue");

            if ($exitValue == 0) {
                if ($finished) {
                    if (is_callable($finished)) {
                        $finished();

                        return;
                    }

                    $dialog->hide();
                    $dialog = new BuildSuccessForm();
                    $dialog->setBuildPath($this->getBuildPath($project));
                    $dialog->setOpenDirectory($this->getBuildPath($project));
                    $dialog->setCreateBatFile($this->getBuildPath($project) . "/{$project->getName()}.jar");

                    $pathToProgram = [Ide::get()->getJrePath() . "/bin/java",  "-jar", "{$this->getBuildPath($project)}/{$project->getName()}.jar"];

                    $dialog->setRunProgram($pathToProgram);

                    $dialog->showAndWait();
                }
            }
        };
        $dialog->setOnExitProcess($onExitProcess);

        ProjectSystem::saveOnlyRequired();
        $buildStartTime = microtime(true);
        ProjectSystem::compileAll(Project::ENV_PROD, $dialog, 'build jar', function ($success) use ($project, $dialog, $onExitProcess, $buildStartTime) {
            if (!$success) { $dialog->stopWithError(); return; }

            static::$buildThread = new Thread(function () use ($project, $dialog, $onExitProcess, $buildStartTime) {
                try {
                    $distDir = $project->getRootDir() . "/build/dist";
                    fs::makeDir("$distDir/lib");

                    $rootDir = $project->getRootDir();
                    $srcGenDir = $rootDir . "/" . $project->getSrcGeneratedDirectory();
                    $srcDir = $rootDir . "/" . $project->getSrcDirectory();

                    $compiledJar = ZipFile::create("$distDir/lib/sprk-compiled-module.jar");
                    fs::scan($srcGenDir, function ($f) use ($compiledJar, $srcGenDir) {
                        if (fs::ext($f) == 'phb') {
                            $rel = FileUtils::relativePath($srcGenDir, $f);
                            $compiledJar->add($rel, new File($f));
                        }
                    });
                    if ($php = PhpProjectBehaviour::get()) {
                        if (!$php->isByteCodeEnabled()) {
                            fs::scan($srcDir, function ($f) use ($compiledJar, $srcDir) {
                                if (fs::ext($f) == 'php' || fs::ext($f) == 'phb') {
                                    $rel = FileUtils::relativePath($srcDir, $f);
                                    $compiledJar->add($rel, new File($f));
                                }
                            });
                        }
                    }
                    $compiledJar = null;

                    $project->copyModuleFiles("$distDir/lib");

                    $extList = '';
                    $libJars = [];

                    foreach ($project->getModules() as $module) {
                        if ($module->getType() != 'jarfile') continue;
                        $name = fs::name($module->getId());
                        if ($libJars[$name]) continue;
                        $libJars[$name] = true;

                        $libFile = "$distDir/lib/$name";
                        if (!fs::isFile($libFile)) continue;

                        try {
                            $zf = new ZipFile($libFile);
                            if ($zf->has('META-INF/services/php.runtime.ext.support.Extension')) {
                                $zf->read('META-INF/services/php.runtime.ext.support.Extension', function ($stat, Stream $stream) use (&$extList) {
                                    $extList .= (string)$stream . "\n\n";
                                });
                            }
                        } catch (\Exception $e) {
                            Logger::warn("Cannot read {$module->getId()}: {$e->getMessage()}");
                        }
                    }

                    if ($extList) {
                        $genDir = "$distDir/gen/META-INF/services";
                        fs::makeDir($genDir);
                        FileUtils::put("$genDir/php.runtime.ext.support.Extension", $extList);
                    }

                    $jarName = $project->getName() ?: 'app';
                    $finalJar = ZipFile::create("$distDir/$jarName.jar");

                    $zf = new ZipFile("$distDir/lib/sprk-compiled-module.jar");
                    $zf->readAll(function ($stat, Stream $stream) use ($finalJar) {
                        $finalJar->addFromString($stat['name'], (string)$stream);
                    });
                    $zf = null;

                    if (fs::isDir("$distDir/gen")) {
                        fs::scan("$distDir/gen", function ($f) use ($finalJar) {
                            $rel = FileUtils::relativePath("$distDir/gen", $f);
                            $finalJar->add($rel, new File($f));
                        });
                    }

                    foreach ($project->getModules() as $module) {
                        if ($module->getType() != 'jarfile') continue;
                        $name = fs::name($module->getId());

                        $libFile = "$distDir/lib/$name";
                        if (!fs::isFile($libFile)) continue;

                        try {
                            $zf = new ZipFile($libFile);
                            $zf->readAll(function ($stat, Stream $stream) use ($finalJar) {
                                $entry = $stat['name'];
                                if (str::startsWith($entry, 'JPHP-INF/sdk/')) return;
                                if ($entry == 'META-INF/MANIFEST.MF') return;
                                if (fs::ext($entry) == 'php') return;
                                $finalJar->addFromString($entry, (string)$stream);
                            });
                            $zf = null;
                        } catch (\Exception $e) {
                            Logger::warn("Cannot merge $name: {$e->getMessage()}");
                        }
                    }

                    $ideRoot = (string) Ide::get()->getOwnFile('');
                    $sourceDirs = ['gui', 'runtime', 'extensions', 'utils', 'framework', 'parser', 'database', 'debug', 'network'];
                    $skipDirs = ['SparkStudio'];
                    foreach ($sourceDirs as $dir) {
                        $parentPath = "$ideRoot/$dir";
                        if (!fs::isDir($parentPath)) continue;

                        $subs = @scandir($parentPath);
                        if (!$subs) continue;
                        foreach ($subs as $subName) {
                            if ($subName == '.' || $subName == '..' || str::startsWith($subName, '.')) continue;
                            if (in_array($subName, $skipDirs)) continue;
                            $subPath = "$parentPath/$subName";
                            if (!is_dir($subPath)) continue;

                            $scanPath = str_replace('\\', '/', $subPath);
                            fs::scan($scanPath, function ($f) use ($finalJar, $scanPath, $subName) {
                                if (is_dir($f)) return;
                                if (str::startsWith(fs::name($f), '.')) return;
                                $rel = $subName . "/" . FileUtils::relativePath($scanPath, str_replace('\\', '/', $f));
                                $finalJar->add($rel, new File(str_replace('\\', '/', $f)));
                            });
                        }
                    }

                    $manifest = "Manifest-Version: 1.0\r\nMain-Class: org.develnext.jphp.ext.javafx.FXLauncher\r\n\r\n";
                    $finalJar->addFromString('META-INF/MANIFEST.MF', $manifest);

                    $finalJar = null;

                    fs::delete("$distDir/lib/sprk-compiled-module.jar");

                    $elapsed = round(microtime(true) - $buildStartTime, 2);

                    Logger::info("Build OK -> $distDir/$jarName.jar");
                    UXApplication::runLater(function () use ($onExitProcess, $dialog, $elapsed) {
                        $dialog->addConsoleLine("");
                        $dialog->addConsoleLine("Build time: {$elapsed}s", 'green');
                        $onExitProcess(0);
                    });

                } catch (\Throwable $e) {
                    $elapsed = round(microtime(true) - $buildStartTime, 2);
                    $msg = get_class($e) . ": {$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}";
                    Logger::warn("Build error: $msg");
                    UXApplication::runLater(function () use ($dialog, $msg, $elapsed) {
                        $dialog->addConsoleLine("[ERROR] $msg", 'red');
                        $dialog->addConsoleLine("Build time: {$elapsed}s", 'gray');
                        $dialog->stopWithError();
                    });
                } finally {
                    static::$buildThread = null;
                }
            });
            static::$buildThread->setName('thread-jar-build-' . str::random());
            static::$buildThread->start();
        });
    }
}

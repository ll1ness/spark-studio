<?php
namespace ide\commands;

use facade\Async;
use ide\editors\AbstractEditor;
use ide\forms\BuildProgressForm;
use ide\Ide;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\project\behaviours\RunBuildProjectBehaviour;
use ide\project\Project;
use ide\project\ProjectConsoleOutput;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;
use ide\ui\Notifications;
use ide\utils\FileUtils;
use php\gui\event\UXEvent;
use php\gui\framework\ScriptEvent;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXTextField;
use php\gui\UXRichTextArea;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\IllegalStateException;
use php\lang\System;
use php\lang\Process;
use php\lang\Thread;
use php\lang\ThreadPool;
use php\lib\arr;
use php\lib\fs;
use php\lib\number;
use php\lib\Str;
use php\time\Time;
use script\TimerScript;
use timer\AccurateTimer;

class ExecuteProjectCommand extends AbstractCommand
{
    /** @var BuildProgressForm */
    protected $processDialog;
    /** @var UXButton */
    protected $actionButton;
    /** @var UXTextField */
    protected $parametersField;

    /** @var Process */
    protected $process;

    /**
     * @var RunBuildProjectBehaviour
     */
    protected $behaviour;

    /**
     * @param RunBuildProjectBehaviour $behaviour
     */
    function __construct(RunBuildProjectBehaviour $behaviour)
    {
        Ide::get()->on('closeProject', function () {
            if ($this->isRunning()) {
                $this->onStopExecute();
            }
        }, __CLASS__);

        $this->behaviour = $behaviour;
    }

    public function getName()
    {
        return 'Запустить проект';
    }

    public function getIcon()
    {
        return 'icons/run16.png';
    }

    public function getAccelerator()
    {
        return 'F9';
    }

    public function getCategory()
    {
        return 'run';
    }

    public function makeUiForHead()
    {
        $this->actionButton = $this->makeGlyphButton();
        $this->actionButton->text = 'Запустить';
        $this->actionButton->on('action', [$this, 'onActionExecute']);

        $this->parametersField = new UXTextField();
        $this->parametersField->text = '-XX:+UseG1GC -Xms128M -Xmx1024m -Dfile.encoding=UTF-8 -Djphp.trace=true org.develnext.jphp.ext.javafx.FXLauncher';
        $this->parametersField->promptText = 'Введите параметры запуска...';

        return [$this->actionButton, $this->parametersField];
    }

    public function isRunning()
    {
        return $this->actionButton && $this->actionButton->text === 'Остановить';
    }

    public function onActionExecute(UXEvent $e = null)
    {
        if ($this->isRunning()) {
            $this->onStopExecute();
        } else {
            $this->onExecute();
        }
    }

    public function onStopExecute(UXEvent $e = null, callable $callback = null)
    {
        $ide = Ide::get();
        $project = $ide->getOpenedProject();

        if ($this->actionButton) {
            $this->actionButton->text = 'Запустить';
        }

        $appPidFile = $project->getFile("application.pid");

        $mainForm = Ide::get()->getMainForm();
        $mainForm->showPreloader('Подождите, останавливаем программу ...');

        $proc = function () use ($appPidFile, $ide, $mainForm, $callback) {
            try {
                $pid = fs::get($appPidFile);

                if ($pid) {
                    if ($ide->isWindows()) {
                        $result = `taskkill /PID $pid /f`;
                    } else {
                        $result = `kill -9 $pid`;
                    }

                    if (!$result) {
                        Notifications::showExecuteUnableStop();
                    }
                } else {
                    if ($this->process instanceof Process) {
                        $this->process->destroy();
                    }

                    Notifications::showExecuteUnableStop();
                }
            } catch (IOException $e) {
                Logger::exception('Cannot stop process', $e);
                Notifications::showExecuteUnableStop();
            } finally {
                $this->processDialog->hide();
                Ide::get()->getMainForm()->hideBottom();
                $appPidFile->delete();
                $this->process = null;
                $mainForm->hidePreloader();

                if ($callback) {
                    $callback();
                }
            }
        };

        if ($appPidFile->exists()) {
            $proc();
        } else {
            $time = 0;

            $timer = new AccurateTimer(100, function () use ($appPidFile, $proc, &$time) {
                $time += 100;

                if ($appPidFile->exists() || $time > 1000 * 25) {
                    $proc();
                    return true;
                }

                return false;
            });
            $timer->start();
        }
    }

    public function tryShowConsole()
    {
        $console = new BuildProgressForm();
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $ide = Ide::get();
        $project = $ide->getOpenedProject();

        $appPidFile = $project->getFile("application.pid");
        $appPidFile->delete();

        $project->trigger('execute');

        if ($project) {
            if ($editor = FileSystem::getSelectedEditor()) {
                $editor->save();
            }

            $this->processDialog = $dialog = new BuildProgressForm();
            $dialog->reduceHeader();
            $dialog->reduceFooter();
            $dialog->removeProgressbar();

            Ide::get()->getMainForm()->showBottom($dialog->layout);

            $dialog->opacity = 0.01;
            $dialog->show();
            $dialog->hide();

            if ($this->actionButton) {
                $this->actionButton->text = 'Остановить';
            }

            $dialog->closeButton->on('action', function () {
                Ide::get()->getMainForm()->hideBottom();
            }, __CLASS__);

            ProjectSystem::saveOnlyRequired();
            ProjectSystem::compileAll(Project::ENV_DEV, $dialog, 'java -cp ... org.develnext.jphp.ext.javafx.FXLauncher', function ($success) use ($dialog, $project, $ide) {
                if (!$success) {
                    $dialog->stopWithError();
                    if ($this->actionButton) {
                        $this->actionButton->text = 'Запустить';
                    }
                    return;
                }

                try {
                    $classPaths = arr::toList($this->behaviour->getSourceDirectories(), $this->behaviour->getProfileModules(['jar']));

                    $jrePath = $ide->getJrePath();
                    $javaBin = 'java';

                    if ($jrePath) {
                        $javaBin = $jrePath . '/bin/java';
                    }

                    $log = [];
                    $log[] = "jrePath = " . ($jrePath ? (string) $jrePath : 'null');
                    $log[] = "javaBin = " . $javaBin;

                    $ideRoot = $ide->getOwnFile('');
                    $log[] = "ideRoot = " . $ideRoot->getPath();
                    $sourceDirs = ['gui', 'runtime', 'extensions', 'utils', 'framework', 'parser', 'database', 'debug', 'network'];

                    foreach ($sourceDirs as $dir) {
                        $parent = new File($ideRoot, $dir);
                        if ($parent->isDirectory()) {
                            $log[] = "scanning $dir ...";
                            foreach ($parent->findFiles() as $sub) {
                                if ($sub->isDirectory() && !str::startsWith($sub->getName(), '.')) {
                                    $classPaths[] = fs::abs($sub);
                                    $log[] = "  added: " . $sub->getName();
                                }
                            }
                        } else {
                            $log[] = "SKIP $dir (not found)";
                        }
                    }

                    $log[] = "classpath count = " . sizeof($classPaths);

                    $joined = str::join($classPaths, File::PATH_SEPARATOR);

                    $args = array_merge(
                        [$javaBin, '-cp', $joined],
                        explode(' ', $this->parametersField ? $this->parametersField->text : '')
                    );

                    try {
                        Stream::putContents($project->getFile('.dn/run-debug.log'), str::join($log, "\n") . "\n---\n" . str::join($args, ' ') . "\n");
                    } catch (\Exception $e) {}

                    $this->process = new Process(
                        $args,
                        $project->getRootDir(),
                        $ide->makeEnvironment()
                    );

                    $this->process = $this->process->start();
                    $dialog->watchProcess($this->process);

                    $dialog->setStopProcedure(function () use ($dialog) {
                        $this->onStopExecute();
                        $dialog->hide();
                        Ide::get()->getMainForm()->hideBottom();
                    });

                    $dialog->setOnExitProcess(function ($exitValue, $hasError) use ($dialog) {
                        if ($this->actionButton) {
                            $this->actionButton->text = 'Запустить';
                        }

                        if (!$exitValue && !$hasError && $dialog->closeAfterDoneCheckbox->selected) {
                            Ide::get()->getMainForm()->hideBottom();
                        }
                    });

                } catch (IOException $e) {
                    if ($this->actionButton) {
                        $this->actionButton->text = 'Запустить';
                    }

                    if (!$dialog->visible) {
                        $dialog->show();
                    }

                    $dialog->stopWithException($e);
                }
            });
        } else {
            $this->process = null;
            UXDialog::show('Ошибка запуска', 'ERROR');
        }
    }
}

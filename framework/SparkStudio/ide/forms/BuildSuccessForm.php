<?php
namespace ide\forms;

use ide\Ide;
use ide\Logger;
use ide\utils\FileUtils;
use php\gui\UXDesktop;
use php\gui\framework\AbstractForm;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXImageView;
use php\gui\UXTextField;
use php\io\File;
use php\io\IOException;
use php\lang\Process;
use php\lib\str;

/**
 * Class BuildSuccessForm
 * @package ide\forms
 *
 * @property UXImageView $icon
 * @property UXButton $runButton
 * @property UXButton $openButton
 * @property UXButton $batButton
 * @property UXButton $exitButton
 * @property UXTextField $pathField
 */
class BuildSuccessForm extends AbstractIdeForm
{
    /**
     * @var callable
     */
    protected $onRun;

    /**
     * @var callable
     */
    protected $onOpenDirectory;

    /**
     * @var string
     */
    protected $buildPath;

    /**
     * @var string|null
     */
    protected $jarPath;

    protected function init()
    {
        $this->icon->image = Ide::get()->getImage('icons/done32.png')->image;
    }

    /**
     * @param callable $onRun
     */
    public function setOnRun($onRun)
    {
        $this->onRun = $onRun;
    }

    /**
     * @param callable $onOpenDirectory
     */
    public function setOnOpenDirectory($onOpenDirectory)
    {
        $this->onOpenDirectory = $onOpenDirectory;
    }

    public function setRunProgram($pathToProgram)
    {
        $pathToProgram = is_array($pathToProgram) ? $pathToProgram : str::split($pathToProgram, ' ');

        $this->onRun = function () use ($pathToProgram) {
            try {
                $this->showPreloader();
                $result = (new Process($pathToProgram, $this->buildPath, Ide::get()->makeEnvironment()))->startAndWait();
                $this->hidePreloader();

                /*$exit = $result->getExitValue();

                if ($exit != 0) {
                    $error = $result->getError()->readFully();

                    if ($error) {
                        UXDialog::showAndWait($error, 'ERROR');
                    }
                } */
            } catch (IOException $e) {
                Ide::showError('Невозможно запустить программу: ' . $e->getMessage());
            }
        };
    }

    public function setOpenDirectory($path)
    {
        $this->onOpenDirectory = function () use ($path) {
            $path = File::of($path);

            $desktop = new UXDesktop();
            $desktop->open($path);
        };
    }

    /**
     * @param string $jarPath полный путь к .jar файлу
     */
    public function setCreateBatFile($jarPath)
    {
        $this->jarPath = $jarPath;
    }

    /**
     * @param string $buildPath
     */
    public function setBuildPath($buildPath)
    {
        $this->buildPath = $buildPath;
    }

    /**
     * @event show
     */
    public function doShow()
    {
        Logger::info("Show build success: buildPath = {$this->buildPath}");

        $this->runButton->free();
        $this->openButton->enabled = !!$this->onOpenDirectory;
        $this->batButton->visible = !!$this->jarPath;
        $this->batButton->managed = !!$this->jarPath;

        $this->pathField->text = File::of($this->buildPath);
    }

    /**
     * @event batButton.action
     */
    public function doBatClick()
    {
        $jarFile = File::of($this->jarPath);
        $jarName = $jarFile->getName();
        $batPath = $this->buildPath . "/" . str::replace($jarName, '.jar', '') . ".bat";

        $jrePath = Ide::get()->getJrePath();
        $javaExe = $jrePath ? str::replace($jrePath, '/', '\\') . '\\bin\\java.exe' : 'java';

        $content = "@echo off\r\n" .
            "setlocal\r\n" .
            "set \"JAVA_HOME={$jrePath}\"\r\n" .
            "cd /d \"%~dp0\"\r\n" .
            "\"{$javaExe}\" -jar \"{$jarName}\" %*\r\n" .
            "if errorlevel 1 pause\r\n" .
            "endlocal\r\n";

        try {
            FileUtils::put($batPath, $content);
            UXDialog::show("Файл запуска создан:\n{$batPath}", 'INFORMATION');
        } catch (\Exception $e) {
            Ide::showError("Не удалось создать .bat файл: " . $e->getMessage());
        }
    }

    /**
     * @event exitButton.action
     */
    public function doExitClick()
    {
        $this->hide();
    }

    /**
     * @event runButton.action
     */
    public function doRunClick()
    {
        call_user_func($this->onRun);
    }

    /**
     * @event openButton.action
     */
    public function doOpenClick()
    {
        call_user_func($this->onOpenDirectory);
    }
}

<?php
namespace ide\commands;

use ide\build\AbstractBuildType;
use ide\build\AntOneJarBuildType;
use ide\editors\AbstractEditor;
use ide\forms\BuildProjectForm;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use php\lang\IllegalArgumentException;

class BuildProjectCommand extends AbstractCommand
{
    /** @var array */
    protected $buildTypes = [];

    public function getName()
    {
        return 'Собрать проект';
    }

    public function getIcon()
    {
        return 'icons/boxArrow16.png';
    }

    public function getCategory()
    {
        return 'run';
    }

    public function makeUiForHead()
    {
        $button = $this->makeGlyphButton();
        return $button;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        /** @var ExecuteProjectCommand $command */
        $command = Ide::get()->getRegisteredCommand(ExecuteProjectCommand::class);

        if ($command && $command->isRunning()) {
            $msg = new MessageBoxForm('Чтобы собрать проект необходимо остановить запущенную программу, остановить её?',
                [
                    'Да, остановить и собрать',
                    'Нет, отмена'
                ]
            );

            if ($msg->showDialog()) {
                if ($msg->getResultIndex() == 0) {
                    $command->onStopExecute(null, function () use ($e, $editor) {
                        $this->onExecute($e, $editor);
                    });
                }

                return;
            }
        }

        $warning = new MessageBoxForm(
            'Проект будет собран в JAR-файл. Продолжить?',
            ['Собрать JAR', 'Отмена']
        );

        if ($warning->showDialog() && $warning->getResultIndex() == 0) {
            foreach ($this->buildTypes as $buildType) {
                if ($buildType instanceof AntOneJarBuildType) {
                    if ($buildType->fetchConfig()) {
                        $buildType->onExecute(Ide::get()->getOpenedProject());
                    }
                    break;
                }
            }
        }
    }

    public function register($any)
    {
        if ($any instanceof AbstractBuildType) {
            $this->buildTypes[get_class($any)] = $any;
        } else {
            throw new IllegalArgumentException();
        }
    }
}
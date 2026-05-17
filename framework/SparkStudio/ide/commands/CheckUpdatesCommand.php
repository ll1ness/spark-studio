<?php
namespace ide\commands;

use ide\account\api\ServiceResponse;
use ide\editors\AbstractEditor;
use ide\forms\UpdateAvailableForm;
use ide\Ide;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\ui\Notifications;
use php\gui\UXApplication;
use php\gui\UXDialog;

class CheckUpdatesCommand extends AbstractCommand
{
    public function getName()
    {
        return _('Проверить обновления');
    }

    public function getIcon()
    {
        return 'icons/update16.png';
    }

    public function withBeforeSeparator()
    {
        return true;
    }

    public function getCategory()
    {
        return 'help';
    }

    public function isAlways()
    {
        return true;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        Ide::get()->getMainForm()->hidePreloader();
        Notifications::info("Мастер Обновлений", "Локальная сборка, автообновление отключено.");
    }
}
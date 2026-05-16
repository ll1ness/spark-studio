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
        Ide::get()->getMainForm()->showPreloader(_('toast.search.updates'));
        waitAsync(100, function () use ($e, $event) {
            //
            // СИСТЕМА ОБНОВЛЕНИЙ В БУДУЩЕМ БУДЕТ ПЕРЕПИСАНА
            //
            // update-service
            // в будущем переделаем код, пока так
            $p = trim(file_get_contents("https://raw.githubusercontent.com/TrueS1gma/DevelWare-Studio/main/_update-service/version.txt"));
            $version = Ide::get()->getConfig()->get('app.version');
            if ($p == $version) {
                Ide::get()->getMainForm()->hidePreloader();
                Notifications::success("Мастер Обновлений", "У вас установлена последняя версия DevelWare Studio " . $version);
            } else {
                Ide::get()->getMainForm()->hidePreloader();
                Notifications::warning("Мастер Обновлений", "Обнаружено новое обновление DevelWare Studio " . $p);
                
                // Отображение формы UpdateAvailableForm
                $form = new UpdateAvailableForm();
                $form->show();
            }
        });
    }
}
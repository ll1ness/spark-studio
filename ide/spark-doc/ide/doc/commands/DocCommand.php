<?php
namespace ide\doc\commands;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\misc\AbstractCommand;

class DocCommand extends AbstractCommand
{
    public function isAlways()
    {
        return true;
    }

    public function getName()
    {
        return 'Справка';
    }

    public function getCategory()
    {
        return 'help';
    }

    public function getIcon()
    {
        return 'icons/help16.gif';
    }

    public function getAccelerator()
    {
        return 'F1';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        Ide::get()->getMainForm()->toast('Сейчас произойдет редирект на страницу ...');
        browse('https://hub.develnext.org/wiki/');
    }
}

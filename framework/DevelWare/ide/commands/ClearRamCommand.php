<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\misc\AbstractCommand;
use php\lang\System;
use php\gui\UXDesktop;

class ClearRamCommand extends AbstractCommand
{
    public function getName()
    {
        return _('Очистить память');
    }

    public function isAlways()
    {
        return true;
    }

    public function getCategory()
    {
        return 'help';
    }

    public function withBeforeSeparator()
    {
        return true;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        System::gc(); 
    }
}

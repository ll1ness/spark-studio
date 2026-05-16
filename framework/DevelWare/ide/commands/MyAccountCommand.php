<?php

namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\misc\AbstractCommand;

class MyAccountCommand extends AbstractCommand
{
    /**
     * MyAccountCommand constructor.
     */
    public function __construct()
    {
        //
    }

    public function getName()
    {
        return _('account.my');
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        if (Ide::accountManager()->isAuthorized()) {
            // Показать контекстное меню
        } else {
            //Ide::accountManager()->authorize(true);
        }
    }

    public function makeMenuItem()
    {
        return null;
    }

    public function isAlways()
    {
        return true;
    }
}

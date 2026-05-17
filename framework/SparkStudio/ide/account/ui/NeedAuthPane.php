<?php
namespace ide\account\ui;

use ide\forms\RegisterForm;
use ide\Ide;
use php\gui\framework\AbstractForm;
use php\gui\framework\EventBinder;
use php\gui\layout\UXAnchorPane;
use php\gui\UXNode;

/**
 * Class NeedAuthPane
 * @package ide\account\ui
 */
class NeedAuthPane extends UXAnchorPane
{
    /**
     * NeedAuthPane constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $binder = new EventBinder($this, $this);
        $binder->setLookup(function (UXNode $context, $id) {
            return $this->lookup("#$id");
        });

        $binder->load();
    }

    /**
     * 
     * @param string $value
     */
    public function setTitle($value)
    {
        
    }

    /**
     * 
     * @event loginButton.action
     */
    public function doLogin()
    {
        Ide::accountManager()->authorize(true);
    }

    /**
     * 
     * @event registerLink.action
     */
    public function doRegister()
    {
        $registerForm = new RegisterForm();
        $registerForm->showAndWait();
    }
}

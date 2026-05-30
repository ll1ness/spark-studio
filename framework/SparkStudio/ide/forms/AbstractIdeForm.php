<?php
namespace ide\forms;

use ide\Ide;
use ide\Logger;
use php\gui\framework\AbstractForm;
use php\gui\framework\DataUtils;
use php\gui\UXForm;
use php\gui\UXLabeled;
use php\gui\UXMenu;
use php\gui\UXMenuBar;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\gui\UXTextInputControl;
use php\lib\str;
use php\util\Regex;

/**
 * Class AbstractIdeForm
 * @package ide\forms
 */
class AbstractIdeForm extends AbstractForm
{
    /**
     * Set to true in subclasses that should open as a native OS window
     * instead of the default in-window modal overlay behaviour.
     * @var bool
     */
    protected $nativeWindow = false;

    public function __construct(UXForm $origin = null)
    {
        parent::__construct($origin);

        if (Ide::isCreated()) {
            $this->owner = Ide::get()->getMainForm();
        }

        Logger::info("Create form " . get_class($this));

        $this->on('show', function () {
            $formName = get_class($this);

            Logger::info("Show form '$formName' ..");

            Ide::get()->trigger('showForm', [$this]);
        }, __CLASS__);

        $this->on('hide', function () {
            $formName = get_class($this);

            Logger::info("Hide form '$formName' ..");

            Ide::get()->trigger('hideForm', [$this]);
        }, __CLASS__);
    }

    protected function init()
    {
        parent::init();

        $l10n = Ide::get()->getL10n();

        $this->title = $l10n->translate($this->title);
        $l10n->translateNode($this->layout);
    }

    public function show()
    {
        // MainForm handles its own display (splash + window).
        if ($this instanceof MainForm) {
            parent::show();
            return;
        }

        // SplashForm is shown before the main window/overlay is ready,
        // it must keep the separate OS window behavior.
        if ($this instanceof SplashForm) {
            parent::show();
            return;
        }

        // Allow subclasses to opt into a native OS window.
        if ($this->nativeWindow) {
            parent::show();

            uiLater(function () {
                if ($this->layout) {
                    $this->size = $this->layout->size;
                }
                $this->centerOnScreen();
            });
            return;
        }

        // Show inside the IDE window as a modal overlay by default.
        if (Ide::isCreated() && Ide::get()->getMainForm()) {
            $this->showInModal();
            return;
        }

        // Fallback: open as a separate window (e.g. before Ide is ready).
        parent::show();

        uiLater(function () {
            if ($this->layout) {
                $this->size = $this->layout->size;
            }
            $this->centerOnScreen();
        });
    }

    /**
     * Show this form's content inside the IDE window as a modal overlay,
     * instead of opening a separate OS window.
     *
     * @param callable|null $onClose called when modal is closed
     */
    public function showInModal(callable $onClose = null)
    {
        if (!$this->layout) {
            return;
        }

        $self = $this;

        uiLater(function () use ($self, $onClose) {
            $self->data('--modal-shown', true);
            $self->trigger('show', null);
            Ide::get()->showModal($self->layout, function () use ($self, $onClose) {
                $self->data('--modal-shown', false);
                $self->trigger('hide', null);
                if ($onClose) {
                    $onClose();
                }
            });
        });
    }

    /**
     * Async modal version of showAndWait().
     * Shows the form in the overlay and calls $onResult with the form instance
     * after it is closed, so callers can read $this->getResult().
     *
     * Usage — migrate from:
     *   $dialog = new SomeForm();
     *   $dialog->showAndWait();
     *   $result = $dialog->getResult();
     *
     * To:
     *   $dialog = new SomeForm();
     *   $dialog->showModal(function ($dialog) {
     *       $result = $dialog->getResult();
     *   });
     *
     * @param callable|null $onResult called when modal closes, receives $this
     */
    public function showModal(callable $onResult = null)
    {
        if (!$this->layout) {
            return;
        }

        $self = $this;

        uiLater(function () use ($self, $onResult) {
            $self->data('--modal-shown', true);
            $self->trigger('show', null);
            Ide::get()->showModal($self->layout, function () use ($self, $onResult) {
                $self->data('--modal-shown', false);
                $self->trigger('hide', null);
                if ($onResult) {
                    $onResult($self);
                }
            });
        });
    }

    /**
     * Convenience alias to close this form's modal overlay.
     */
    public function closeModal()
    {
        if (Ide::isCreated() && Ide::get()->getMainForm()) {
            Ide::get()->hideModal();
        }
    }

    /**
     * Override hide() to support the in-window modal overlay.
     * If the form is currently shown as a modal overlay, close that instead.
     */
    public function hide()
    {
        if ($this->data('--modal-shown')) {
            $this->closeModal();
        } else {
            parent::hide();
        }
    }
}

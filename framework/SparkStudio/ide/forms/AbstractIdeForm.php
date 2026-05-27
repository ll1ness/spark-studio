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
        parent::show();

        if ($this instanceof MainForm) {
            return;
        }

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

        // Load events/bindings if not already done (constructor handles this)
        uiLater(function () use ($onClose) {
            $this->trigger('show', null);
            Ide::get()->showModal($this->layout, function () use ($onClose) {
                $this->trigger('hide', null);
                if ($onClose) {
                    $onClose();
                }
            });
        });
    }

}
<?php
namespace ide\forms\mixins;

use php\gui\framework\AbstractForm;

trait DialogFormMixin
{
    protected $result;

    /**
     * @param $x
     * @param $y
     *
     * @return bool
     */
    public function showDialog($x = null, $y = null)
    {
        /** @var AbstractForm|DialogFormMixin $this */

        $this->centerOnScreen();

        if ($x !== null) {
            $this->x = $x;
        }

        if ($y !== null) {
            $this->y = $y;
        }

        $this->showAndWait();
        return $this->result !== null;
    }

    /**
     * Async version of showDialog() that renders inside the IDE window.
     *
     * @param callable|null $onResult called with (bool $accepted, $this)
     */
    public function showDialogModal(callable $onResult = null)
    {
        /** @var AbstractForm|DialogFormMixin $this */

        $this->centerOnScreen();

        if (\ide\Ide::isCreated() && \ide\Ide::get()->getMainForm()) {
            $this->showModal(function ($self) use ($onResult) {
                if ($onResult) {
                    $onResult($self->result !== null, $self);
                }
            });
        } else {
            $this->showAndWait();
            if ($onResult) {
                $onResult($this->result !== null, $this);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
}
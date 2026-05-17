<?php
namespace ide\forms;

use ide\Ide;
use ide\ui\Notifications;
use php\gui\event\UXEvent;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXDesktop;
use php\gui\UXLabel;
use php\gui\UXTextArea;
use php\lib\fs;
use IOException;

/**
 * Class UpdateAvailableForm
 * @package ide\forms
 *
 * @property UXTextArea $descriptionField
 * @property UXLabel $nameLabel
 *
 * @property UXButton $youtubeButton
 * @property UXButton $downloadButton
 */
class UpdateAvailableForm extends AbstractIdeForm
{
    /**
     * @var string
     */
    protected $videoLink;

    /**
     * @var string
     */
    protected $downloadLink;

    protected function init()
    {
        parent::init();

        $this->icon->image = ico('update32')->image;
    }


    /**
     * @param $data
     * @param bool $always
     * @return bool
     */
    public function tryShow($data, $always = false)
    {
        $version = Ide::get()->getConfig()->get('app.version');

        $this->descriptionField->text = "Локальная сборка, автообновление отключено.";
        $this->nameLabel->text = "Spark Studio " . $version;

        return true;
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $this->downloadButton->enabled = false;
        $this->youtubeButton->enabled = false;
		$this->descriptionField->text = "Локальная сборка, автообновление отключено.";
		$this->nameLabel->text = "Spark Studio " . Ide::get()->getConfig()->get('app.version');
    }

    /**
     * @event youtubeButton.action
     */
    public function doYoutube()
    {
        // Локальная сборка — ссылка на скачивание неактивна
    }

    /**
     * @event downloadButton.action
     */
    public function doDownload()
    {
        // Локальная сборка — обновление отключено
    }

    /**
     * @event close
     * @event cancelButton.action
     * @param UXEvent $e
     */
    public function doCancel(UXEvent $e)
    {
        $dialog = new MessageBoxForm('Вы уверены, что не хотите обновится до новой версии?', ['Да, обновиться позже', 'Отмена'], $this);

        if ($dialog->showDialog() && $dialog->getResultIndex() == 1) {
            $e->consume();
        } else {
            $this->hide();
        }
    }
}
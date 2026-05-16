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
        $p = trim(file_get_contents("https://raw.githubusercontent.com/TrueS1gma/DevelWare-Studio/main/_update-service/version.txt"));
		$desc = file_get_contents("https://raw.githubusercontent.com/TrueS1gma/DevelWare-Studio/main/_update-service/description.txt");
		$version = Ide::get()->getConfig()->get('app.version');

        $this->descriptionField->text = $desc;
        $this->nameLabel->text = "DevelWare Studio ".$p;
        //$this->downloadLink = $data['url']; зачем если обновления автомтаические
        //$this->videoLink = "https://github.com/TrueS1gma/DevelWare-Studio/releases/tag/".$p;

        //Ide::get()->setUserConfigValue('lastUpdateVersion', $data['hash']);


        return true;
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $this->downloadButton->enabled = true;
        $this->youtubeButton->enabled = true;
		$desc = file_get_contents("https://raw.githubusercontent.com/TrueS1gma/DevelWare-Studio/main/_update-service/description.txt");
		$this->descriptionField->text = $desc;
		
		$p = trim(file_get_contents("https://raw.githubusercontent.com/TrueS1gma/DevelWare-Studio/main/_update-service/version.txt"));
		$this->nameLabel->text = "DevelWare Studio ".$p;
    }

    /**
     * @event youtubeButton.action
     */
    public function doYoutube()
    {
        $p = trim(file_get_contents("https://raw.githubusercontent.com/TrueS1gma/DevelWare-Studio/main/_update-service/version.txt"));
		$link = "https://github.com/TrueS1gma/DevelWare-Studio/releases/tag/".$p;
		
		$desktop = new UXDesktop();
        $desktop->browse($link);
    }

    /**
     * @event downloadButton.action
     */
    public function doDownload()
    {
        fs::copy("./lib/update-service.jar","./update-service.jar");
		waitAsync(2000, function() {
			try {
				execute("java -jar update-service.jar");
			} catch (IOException $e) {
				alert('Произошла ошибка - ' . $e->getMessage());
			}
			
			waitAsync(2000, function($e = null) {
				Ide::get()->getMainForm()->trigger('close', $e);
				$this->hide();
			});
		});
		
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
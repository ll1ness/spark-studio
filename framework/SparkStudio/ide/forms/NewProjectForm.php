<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\systems\ProjectSystem;
use php\gui\UXDirectoryChooser;
use php\gui\UXImageView;
use php\gui\UXTextField;
use php\io\File;
use php\lib\fs;
use php\lib\Str;
use php\util\Regex;

/**
 *
 * @property UXImageView $icon
 * @property UXTextField $pathField
 * @property UXTextField $nameField
 * @property UXTextField $packageField
 *
 * Class NewProjectForm
 * @package ide\forms
 */
class NewProjectForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;

    /** @var UXDirectoryChooser */
    protected $directoryChooser;

    public function init()
    {
        parent::init();

        $this->directoryChooser = new UXDirectoryChooser();

        $this->icon->image = Ide::get()->getImage('icons/new32.png')->image;
        $this->modality = 'APPLICATION_MODAL';
        $this->title = 'Новый проект';

        $this->pathField->text = $projectDir = Ide::get()->getUserConfigValue('projectDirectory');
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $this->nameField->requestFocus();
    }

    /**
     * @event pathButton.action
     */
    public function doChoosePath()
    {
        $path = $this->directoryChooser->execute();

        if ($path !== null) {
            $this->pathField->text = $path;

            Ide::get()->setUserConfigValue('projectDirectory', $path);
        }
    }

    /**
     * @event nameField.keyDown-Enter
     * @event createButton.action
     */
    public function doCreate()
    {
        $templates = Ide::get()->getProjectTemplates();
        $template = null;

        foreach ($templates as $t) {
            $template = $t;
            break;
        }

        if (!$template) {
            Ide::showError(_('project.new.error.no.template'));
            return;
        }

        $path = File::of($this->pathField->text);

        if (!$path->isDirectory()) {
            if (!$path->mkdirs()) {
                Ide::showError(_('project.new.error.create.project.directory'));
                return;
            }
        }

        $name = str::trim($this->nameField->text);

        if (!$name) {
            Ide::showError(_('project.new.error.name.required'));
            return;
        }

        if (!fs::valid($name)) {
            Ide::showError(_('project.new.error.name.invalid') . " \n\n$name");
            return;
        }

        $package = str::trim($this->packageField->text);

        $regex = new Regex('^[a-z\\_]{2,15}$');

        if (!$regex->test($package)) {
            Ide::showError(_('project.new.error.package.invalid') . "\n* " . _('project.new.error.package.invalid.description'));
            return;
        }

        $this->hide();
        $filename = File::of("$path/$name/$name.dnproject");

        ProjectSystem::close(false);

        uiLater(function () use ($template, $filename, $package) {
            app()->getMainForm()->showPreloader('Создание проекта ...');
            try {
                ProjectSystem::create($template, $filename, $package);
            } finally {
                app()->getMainForm()->hidePreloader();
            }
        });
    }

    /**
     * @event cancelButton.click
     */
    public function doCancel()
    {
        $this->hide();
    }
}
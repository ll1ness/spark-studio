<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\forms\MessageBoxForm;
use ide\forms\OpenProjectForm;
use ide\forms\SaveProjectForLibraryForm;
use ide\Ide;
use ide\library\IdeLibraryProjectResource;
use ide\misc\AbstractCommand;
use ide\project\Project;

class SaveProjectForLibraryCommand extends AbstractProjectCommand
{
    public function getName()
    {
        return _('menu.project.save.in.library');
    }

    public function getIcon()
    {
        return 'icons/blocks16.png';
    }

    public function getCategory()
    {
        return 'project';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $dialog = new SaveProjectForLibraryForm();

            if ($dialog->showDialog()) {
                $resource = $dialog->getResult();

                if ($resource instanceof IdeLibraryProjectResource) {
                    $resource->save();
                    $project->save();

                    Ide::get()->getLibrary()->update();
                } else {
                    Ide::toast(_('toast.project.save.in.library.fail'));
                }
            }
        } else {
            Ide::showMessage(_('alert.project.save.in.library.fail'));
        }
    }
}

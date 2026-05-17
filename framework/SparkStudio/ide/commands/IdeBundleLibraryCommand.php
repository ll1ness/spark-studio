<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\misc\AbstractCommand;
use php\gui\UXDesktop;
use php\io\File;

class IdeBundleLibraryCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Пакеты расширений';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $dir = Ide::get()->getLibrary()->getResourceDirectory('bundles');
        if ($dir) {
            $desktop = new UXDesktop();
            $desktop->open(File::of($dir));
        }
    }
}
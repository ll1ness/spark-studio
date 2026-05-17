<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use php\gui\UXMenuItem;
use php\lib\items;

/**
 * Class DublicateMenuCommand
 * @package ide\formats\form\context
 */
class DublicateMenuCommand extends AbstractMenuCommand
{
    public function getName()
    {
        return 'Дублировать';
    }

    public function getAccelerator()
    {
        return 'Ctrl+D';
    }

    public function getIcon()
    {
        return 'icons/dublicate16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $copyCommand = new CopyMenuCommand();
        $pasteCommand = new PasteMenuCommand();

        $copyCommand->onExecute($e, $editor, true);
        $pasteCommand->onExecute($e, $editor);
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        /** @var FormEditor $editor */
        $item->disable = !items::first($editor->getDesigner()->getSelectedNodes());
    }
}
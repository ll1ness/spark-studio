<?php
namespace ide\editors\form;

use ide\misc\EventHandlerBehaviour;
use php\gui\layout\UXVBox;
use php\gui\UXNode;

class FormEditorLeftPaneUi
{
    use EventHandlerBehaviour;

    protected $ui;
    protected $objectTreeList;
    protected $eventListPane;
    protected $behaviourPane;
    protected $objectTreeUi;

    public function __construct()
    {
        $box = new UXVBox();
        $box->fillWidth = true;
        $box->spacing = 2;
        $this->ui = $box;
    }

    public function addObjectTreeList(IdeObjectTreeList $list)
    {
        $this->objectTreeList = $list;
        $node = $list->makeUi();
        $this->objectTreeUi = $node;
        $this->ui->children->insert(0, $node);
    }

    public function setEventListPane(IdeEventListPane $pane)
    {
        $this->eventListPane = $pane;
        $pane->makeUi();
    }

    public function setBehaviourPane(IdeBehaviourPane $pane)
    {
        $this->behaviourPane = $pane;
    }

    public function getEventListPane()
    {
        return $this->eventListPane;
    }

    public function getBehaviourPane()
    {
        return $this->behaviourPane;
    }

    public function getObjectTreeList()
    {
        return $this->objectTreeList;
    }

    public function makeUi()
    {
        return $this->ui;
    }

    public function refresh()
    {
    }

    public function refreshObjectTreeList($targetId = null)
    {
        if ($this->objectTreeList) {
            $this->objectTreeList->update($targetId);
        }
    }

    public function updateEventList($targetId)
    {
        if ($this->eventListPane) {
            $this->eventListPane->update($targetId);
        }
    }

    public function updateBehaviours($targetId)
    {
    }

    public function hideBehaviourPane()
    {
    }

    public function hideEventListPane()
    {
    }

    public function showEventListPane()
    {
    }

    public function showBehaviourPane()
    {
    }

    public function update($targetId, $target = null)
    {
        $this->updateBehaviours($targetId);
        $this->updateEventList($targetId);
        $this->refreshObjectTreeList($targetId);
    }
}

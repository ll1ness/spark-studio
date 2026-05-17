<?php
namespace ide\project\behaviours;

use ide\account\api\ProjectArchiveService;
use ide\account\api\ServiceResponse;
use ide\account\ui\NeedAuthPane;
use ide\forms\area\ShareProjectArea;
use ide\Ide;
use ide\project\AbstractProjectBehaviour;
use ide\project\control\CommonProjectControlPane;

class ShareProjectBehaviour extends AbstractProjectBehaviour
{
    protected $uiAuthPane = null;
    protected $uiSyncPane = null;
    protected $projectService = null;
    protected $data = null;

    public function inject()
    {
        $this->project->on('makeSettings', [$this, 'doMakeSettings']);
        $this->project->on('updateSettings', [$this, 'doUpdateSettings']);
        $this->project->on('close', [$this, 'doClose']);
    }

    public function getPriority()
    {
        return self::PRIORITY_COMPONENT;
    }

    public function doClose()
    {
    }

    public function doUpdateSettings(CommonProjectControlPane $editor = null)
    {
        if ($this->uiSyncPane) {
           
        }

        if ($this->uiAuthPane) {
           
        }

        if (Ide::accountManager()->isAuthorized()) {
            $uid = Ide::project()->getIdeServiceConfig()->get('projectArchive.uid');

            if ($uid) {
                // 
                // $this->projectService->getAsync($uid, function (ServiceResponse $response) {
                //     if ($response->isSuccess()) {
                //         $this->data = $response->result();
                //         $this->uiSyncPane->setData($this->data);
                //     } else {
                //         $this->uiSyncPane->setData(null);
                //         $this->data = $response->result();
                //     }
                // });
            } else {
                // $this->uiSyncPane->setData(null);
            }
        } else {
            if ($this->uiAuthPane) {
                // $editor->addSettingsPane($this->uiAuthPane);
            }
        }
    }

    public function doMakeSettings(CommonProjectControlPane $editor)
    {
        $this->uiAuthPane = null;
        $this->uiSyncPane = null;

        // Ide::accountManager()->bind('login', [$this, 'doUpdateSettings']);
        // Ide::accountManager()->bind('logout', [$this, 'doUpdateSettings']);
    }
}

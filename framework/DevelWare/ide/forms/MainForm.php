<?php
namespace ide\forms;

use ide\editors\form\IdeTabPane;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\IdeConfigurable;
use ide\IdeException;
use ide\Logger;
use ide\project\templates\DefaultGuiProjectTemplate;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;
use ide\systems\WatcherSystem;
use ide\systems\IdeSystem;
use ide\utils\FileUtils;
use ide\utils\UiUtils;
use php\desktop\HotKeyManager;
use php\desktop\Robot;
use php\gui\designer\UXDesigner;
use php\gui\designer\UXDirectoryTreeValue;
use php\gui\designer\UXDirectoryTreeView;
use php\gui\designer\UXFileDirectoryTreeSource;
use php\gui\dock\UXDockNode;
use php\gui\dock\UXDockPane;
use php\gui\event\UXEvent;
use php\gui\event\UXKeyboardManager;
use php\gui\event\UXKeyEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\framework\Preloader;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXAlert;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXForm;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXMenu;
use php\gui\UXMenuBar;
use php\gui\UXMenuButton;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\gui\UXScreen;
use php\gui\UXSplitPane;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXTextField;
use php\gui\UXTreeView;
use php\io\Stream;
use php\util\Scanner;
use php\io\File;
use php\lib\fs;
use php\lib\str;
use script\TimerScript;
use system\DFFIGUI;

/**
 * @property UXTabPane $fileTabPane
 * @property UXTabPane $projectTabs
 * @property UXVBox $properties
 * @property UXAnchorPane $directoryTree
 * @property UXTreeView $projectTree
 * @property UXHBox $topBar
 * @property UXHBox $headPane
 * @property UXHBox $headRightPane
 * @property UXVBox $contentVBox
 * @property UXSplitPane $splitTree
 * @property UXSplitPane $centerSplit
 * @property UXHBox $footerPane
 * @property UXHBox $footerLeftPane
 */
class MainForm extends AbstractIdeForm
{
    use IdeConfigurable;

    /**
     * @var UXMenuBar
     */
    public $mainMenu;

    /**
     * @var UXAnchorPane
     */
    private $consolePane;

    /**
     * @var UXMenuButton[]
     */
    public $toolbarCategoryMenus = [];

    public function __construct()
    {
        parent::__construct();

        $this->consolePane = new UXAnchorPane();

        foreach ($this->topBar->children as $one) {
            if ($one instanceof UXMenuBar) {
                $this->mainMenu = $one;
                break;
            }
        }

        if (!$this->mainMenu) {
            throw new IdeException("Cannot find main menu on main form");
        }
    }

    public function findSubMenu($string)
    {
        /** @var UXMenu $one */
        foreach ($this->mainMenu->menus as $one) {
            if ($one->id == $string) {
                return $one;
            }
        }

        return null;
    }

    protected function init()
    {
        parent::init();

        $this->opacity = 0.01;

        Ide::get()->on('start', function () {
            $this->opacity = 1;
        });

        $mainMenu = $this->mainMenu;

        $this->headRightPane->spacing = 5;

        $pane = UXTabPane::createDefaultDnDPane();

        $parent = $this->fileTabPane->parent;
        $this->fileTabPane->free();

        /** @var UXTabPane $tabPane */
        $tabPane = $pane ? $pane->children[0] : new UXTabPane();
        $tabPane->id = 'fileTabPane';
        $tabPane->tabClosingPolicy = 'ALL_TABS';
        $tabPane->classes->add('dn-file-tab-pane');

        if ($pane) {
            UXAnchorPane::setAnchor($pane, 0);
            $parent->add($pane);
        } else {
            $parent->add($tabPane);
        }

        $tree = new UXDirectoryTreeView();
        $tree->position = [0, 0];
        $tree->style = UiUtils::fontSizeStyle();
        $this->directoryTree->add($tree);

        UXAnchorPane::setAnchor($tree, 0);

        Ide::get()->bind('shutdown', function () {
            $this->ideConfig()->set("splitTree.dividerPositions", $this->splitTree->dividerPositions);
            $this->ideConfig()->set("centerSplit.dividerPositions", $this->centerSplit->dividerPositions);
        });

        Ide::get()->bind('openProject', function () use ($tree) {
            $project = Ide::project();
            $project->getTree()->setView($tree);

            $tree->treeSource = $project->getTree()->createSource();

            $tree->root->expanded = true;
            $project->getConfig()->loadTreeState($project->getTree());

            $this->updateFooter();
        });

        Ide::get()->bind('afterCloseProject', function () use ($tree) {
            if ($tree->treeSource) {
                $tree->treeSource->shutdown();
            }
            $tree->treeSource = null;

            $this->footerLeftPane->children->clear();
        });
    }

    public function updateFooter()
    {
        $this->footerLeftPane->children->clear();

        $logoFile = IdeSystem::getOwnFile('logo.png');
        if (fs::isFile($logoFile)) {
            $image = new UXImage(File::of($logoFile));
            $icon = new UXImageView($image);
            $icon->size = [16, 16];
            $this->footerLeftPane->add($icon);
        }

        $label = new UXLabel('SparkStudio');
        $label->style = '-fx-font-weight: bold; -fx-text-fill: #d4d4d4;';
        $this->footerLeftPane->add($label);

        $version = Ide::get()->getVersion();
        if ($version) {
            $verLabel = new UXLabel($version);
            $verLabel->style = '-fx-text-fill: #888888;';
            $this->footerLeftPane->add($verLabel);
        }

        $project = Ide::project();
        if ($project) {
            $projLabel = new UXLabel('(' . $project->getName() . ')');
            $projLabel->style = '-fx-text-fill: #888888;';
            $this->footerLeftPane->add($projLabel);
        }
    }

    public function defineMenuGroup($id, $text, $prepend = false)
    {
        $id = str::upperFirst($id);

        $menu = null;

        foreach ($this->mainMenu->menus as $one) {
            if ($one->id == "menu$id") {
                $menu = $one;
                break;
            }
        }

        if ($menu == null) {
            $menu = new UXMenu($text);
            $menu->id = "menu$id";

            if ($prepend) {
                $this->mainMenu->menus->insert(0, $menu);
            } else {
                $this->mainMenu->menus->add($menu);
            }
        } else {
            $menu->text = $text;
        }

        return $menu;
    }

    /**
     * @event showing
     */
    public function doShowing()
    {
        Ide::get()->getL10n()->translateNode($this->mainMenu);
    }

    public function show()
    {
        parent::show();
        Logger::info("Show main form ...");

        $ideLanguage = Ide::get()->getLanguage();

        $menu = $this->findSubMenu('menuL10n');
        $menu->items->clear();

        if ($ideLanguage) {
            $menu->graphic = Ide::get()->getImage(new UXImage($ideLanguage->getIcon()));
        }

        foreach (Ide::get()->getLanguages() as $language) {
            $item = new UXMenuItem($language->getTitle(), Ide::get()->getImage(new UXImage($language->getIcon())));

            if ($language->getTitle() != $language->getTitleEn()) {
                $item->text .= ' (' . $language->getTitleEn() . ')';
            }

            $item->on('action', function () use ($language, $item, $menu) {
                $msg = new MessageBoxForm($language->getRestartMessage(), [$language->getRestartYes(), $language->getRestartNo()]);
                $msg->makeWarning();
                $msg->showDialog();

                $menu->graphic = Ide::get()->getImage(new UXImage($language->getIcon()));
                Ide::get()->setUserConfigValue('ide.language', $language->getCode());

                if ($msg->getResultIndex() == 0) {
                    Ide::get()->restart();
                }
            });

            $menu->items->add($item);
        }

        $screen = UXScreen::getPrimary();

        $this->width  = Ide::get()->getUserConfigValue(get_class($this) . '.width', $screen->bounds['width'] * 0.75);
        $this->height = Ide::get()->getUserConfigValue(get_class($this) . '.height', $screen->bounds['height'] * 0.75);

        if ($this->width < 300 || $this->height < 200) {
            $this->width = $screen->bounds['width'] * 0.75;
            $this->height = $screen->bounds['height'] * 0.75;
        }

        $this->centerOnScreen();

        $this->x = Ide::get()->getUserConfigValue(get_class($this) . '.x', 0);
        $this->y = Ide::get()->getUserConfigValue(get_class($this) . '.y', 0);

        if ($this->x > $screen->visualBounds['width'] - 10 || $this->y > $screen->visualBounds['height'] - 10 ||
            $this->x < -999 || $this->y < -999) {
            $this->x = $this->y = 50;
        }

        $this->maximized = Ide::get()->getUserConfigValue(get_class($this) . '.maximized', true);

        $this->observer('maximized')->addListener(function ($old, $new) {
            Ide::get()->setUserConfigValue(get_class($this) . '.maximized', $new);
        });

		$a = new DFFIGUI;
		$a->setUseImmersiveDarkMode($this, true);

        foreach (['width', 'height', 'x', 'y'] as $prop) {
            $this->observer($prop)->addListener(function ($old, $new) use ($prop) {
                if ($this->iconified) {
                    return;
                }

                if (!$this->maximized) {
                    Ide::get()->setUserConfigValue(get_class($this) . '.' . $prop, $new);
                }
            });
        }

        uiLater(function () {
            $this->updateFooter();

            if ($this->ideConfig()->has('splitTree.dividerPositions')) {
                $this->splitTree->dividerPositions = $this->ideConfig()->getArray('splitTree.dividerPositions', [0.2]);
            }
        });
    }

    public function doClose(UXEvent $e = null)
    {
        Logger::info("Close main form ...");

        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $dialog = new MessageBoxForm(_('exit.project.message', $project->getName()), [
                'yes' => _('exit.project.yes'),
                'no'  => _('exit.project.no'),
                'abort' => _('exit.project.abort')
            ]);
            $dialog->title = _('exit.project.title');

            if ($dialog->showDialog()) {
                $result = $dialog->getResult();

                if ($result == 'yes') {
                    Logger::info("Remember the last project = yes!");
                    Ide::get()->setUserConfigValue('lastProject', $project->getProjectFile());
                } elseif ($result == 'abort') {
                    if ($e) {
                        $e->consume();
                    }
                    return;
                } else {
                    Logger::info("Cancel closing main form.");
                    Ide::get()->setUserConfigValue('lastProject', null);
                }

                Ide::get()->shutdown();
            } else {
                if ($e) {
                    $e->consume();
                }
            }
        } else {
            Ide::get()->setUserConfigValue('lastProject', null);

            $dialog = new MessageBoxForm(_('exit.message'), [_('exit.yes'), _('exit.no')]);
            if ($dialog->showDialog() && $dialog->getResultIndex() == 0) {
                $this->hide();
                Ide::get()->shutdown();
            } else {
                if ($e) {
                    $e->consume();
                }
            }
        }
    }

    public function getHeadPane()
    {
        return $this->headPane;
    }

    public function getHeadRightPane()
    {
        return $this->headRightPane;
    }

    public function getProjectTree()
    {
        return $this->projectTree;
    }

    public function getFooterPane()
    {
        return $this->footerPane;
    }

    public function getFooterLeftPane()
    {
        return $this->footerLeftPane;
    }

    public function hideBottom()
    {
        $this->showBottom(null);
    }

    public function showBottom(UXNode $content = null)
    {
        if ($content) {
            $this->consolePane->children->clear();

            $content->height = (int) Ide::get()->getUserConfigValue('mainForm.consoleHeight', 350);

            $content->observer('height')->addListener(function ($old, $new) use ($content) {
                if (!$content->isFree()) {
                    Ide::get()->setUserConfigValue('mainForm.consoleHeight', $new);
                }
            });

            UXAnchorPane::setAnchor($content, 0);

            $this->consolePane->add($content);

            $items = $this->centerSplit->items;
            if (!$items->has($this->consolePane)) {
                $items->insert(max(0, $items->count - 1), $this->consolePane);
                uiLater(function () {
                    $this->centerSplit->dividerPositions = [0.6, 0.75];
                });
            }
        } else {
            $items = $this->centerSplit->items;
            if ($items->has($this->consolePane)) {
                $items->remove($this->consolePane);
            }
            $this->consolePane->children->clear();
        }
    }
}

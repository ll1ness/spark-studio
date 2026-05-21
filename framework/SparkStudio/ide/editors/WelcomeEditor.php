<?php
namespace ide\editors;

use ide\commands\NewProjectCommand;
use ide\commands\OpenProjectCommand;
use ide\forms\OpenProjectForm;
use ide\Ide;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXStackPane;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXHyperlink;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXSeparator;
use php\gui\text\UXFont;
use php\io\File;
use php\lib\fs;

class WelcomeEditor extends AbstractEditor
{
    public function isCloseable()
    {
        return false;
    }

    public function getTitle()
    {
        return _('welcome.title');
    }

    public function isAutoClose()
    {
        return false;
    }

    public function load()
    {
    }

    public function save()
    {
    }

    public function makeUi()
    {
        $root = new UXStackPane();
        $root->style = '-fx-background-color: #080808;';

        $content = new UXVBox();
        $content->alignment = 'CENTER';
        $content->spacing = 32;
        $content->maxWidth = 680;

        $logo = new UXLabel('SPARKSTUDIO');
        $logo->font = UXFont::of('System Bold', 36);
        $logo->alignment = 'CENTER';
        $logo->maxWidth = 99999;

        $columns = new UXHBox();
        $columns->spacing = 60;
        $columns->alignment = 'TOP_LEFT';
        $columns->maxWidth = 99999;

        $leftCol = new UXVBox();
        $leftCol->spacing = 24;
        $leftCol->prefWidth = 260;

        $leftCol->children->addAll([
            $this->makeSection('START', [
                $this->makeActionButton(_('welcome.project.create'), 'res://.data/img/icons/new32.png', function () {
                    Ide::get()->executeCommand(NewProjectCommand::class);
                }),
                $this->makeActionButton(_('welcome.project.open'), 'res://.data/img/icons/open32.png', function () {
                    Ide::get()->executeCommand(OpenProjectCommand::class);
                }),
            ]),
            $this->makeSection('CUSTOMIZE', [
                $this->makeLink('Settings', function () {}),
                $this->makeLink('Keyboard Shortcuts', function () {}),
            ]),
            $this->makeSection('HELP', [
                $this->makeLink('Welcome Video', function () {}),
                $this->makeLink('Documentation', function () {}),
            ]),
        ]);

        $rightCol = new UXVBox();
        $rightCol->spacing = 24;
        $rightCol->prefWidth = 300;

        $rightCol->children->add($this->makeRecentSection());

        $columns->children->addAll([$leftCol, $rightCol]);
        $content->children->addAll([$logo, $columns]);
        $root->children->add($content);

        return $root;
    }

    private function makeSection($title, array $items)
    {
        $box = new UXVBox();
        $box->spacing = 6;
        $box->alignment = 'CENTER';

        $heading = new UXLabel($title);
        $heading->font = UXFont::of('System Bold', 12);
        $box->children->add($heading);

        foreach ($items as $item) {
            $box->children->add($item);
        }

        return $box;
    }

    private function makeActionButton($text, $iconRes, callable $onClick)
    {
        $btn = new UXButton($text);
        $btn->maxWidth = 200;

        try {
            $img = new UXImage($iconRes);
            $img->size = [20, 20];
            $icon = new UXImageView($img);
            $icon->size = [20, 20];
            $btn->graphic = $icon;
            $btn->graphicTextGap = 10;
        } catch (\Exception $e) {
        }

        $btn->on('click', $onClick);

        return $btn;
    }

    private function makeLink($text, callable $onClick)
    {
        $link = new UXHyperlink($text);
        $link->maxWidth = 200;

        $link->on('action', $onClick);

        return $link;
    }

    private function makeRecentSection()
    {
        $box = new UXVBox();
        $box->spacing = 6;
        $box->alignment = 'CENTER';

        $heading = new UXLabel('RECENT');
        $heading->font = UXFont::of('System Bold', 12);
        $box->children->add($heading);

        $children = $box->children;
        $hasRecent = false;

        $recentList = Ide::get()->getUserConfigValue('recentProjects', '');

        if ($recentList) {
            $paths = explode(',', $recentList);

            foreach ($paths as $path) {
                $path = trim($path);

                if (!$path || !fs::exists($path)) {
                    continue;
                }

                $file = new File($path);
                $name = $file->getName();
                $parent = $file->getParent();

                $itemBox = new UXVBox();
                $itemBox->spacing = 1;

                $nameLabel = new UXLabel($name);
                $nameLabel->maxWidth = 99999;
                $pathLabel = new UXLabel($parent);
                $pathLabel->maxWidth = 99999;

                $itemBox->children->addAll([$nameLabel, $pathLabel]);

                $container = new UXAnchorPane();
                $container->style = '-fx-padding: 6 12; -fx-cursor: hand;';
                UXAnchorPane::setAnchor($itemBox, 4.0);
                $container->children->add($itemBox);

                $path_ = $path;
                $container->on('click', function () use ($path_) {
                    Ide::get()->executeCommand(OpenProjectCommand::class);
                });

                $children->add($container);
                $hasRecent = true;
            }
        }

        if (!$hasRecent) {
            $noRecent = new UXLabel('No recent projects');
            $noRecent->alignment = 'CENTER';
            $noRecent->maxWidth = 99999;
            $children->add($noRecent);
        }

        $children->add(new UXSeparator());

        $children->add($this->makeLink('Open Project File...', function () {
            $dialog = new OpenProjectForm();
            $dialog->showDialog();
        }));

        return $box;
    }
}

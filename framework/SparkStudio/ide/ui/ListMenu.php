<?php
namespace ide\ui;

use action\Animation;
use ide\Ide;
use ide\utils\UiUtils;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\lib\arr;
use php\lib\str;

class ListMenuCategory
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}

class ListMenu extends UXListView
{
    protected $descriptionGetter = null;
    protected $nameGetter = null;
    protected $iconGetter = null;
    protected $categoryGetter = null;

    protected $nameThin = false;

    protected $thin = false;

    public function __construct()
    {
        parent::__construct();

        $this->classes->add('sprk-list-menu');
        $this->fixedCellSize = 50;

        $this->setCellFactory(function (UXListCell $view, $page) {
            $this->cellRender($view, $page);
        });
    }

    public function setCategoryGetter(callable $callback)
    {
        $this->categoryGetter = $callback;
    }

    public function setGroupedItems(array $items, array $categoryOrder = [])
    {
        $groups = [];

        foreach ($items as $item) {
            $cat = $this->categoryGetter ? call_user_func($this->categoryGetter, $item) : null;

            if ($item instanceof ListMenuCategory) {
                $cat = $item->name;
            } elseif ($cat === null) {
                $groups[''][] = $item;
                continue;
            }

            if (!isset($groups[$cat])) {
                $groups[$cat] = [];
            }

            $groups[$cat][] = $item;
        }

        $flat = [];

        if ($categoryOrder) {
            foreach ($categoryOrder as $catName) {
                if (isset($groups[$catName])) {
                    $flat[] = new ListMenuCategory($catName);
                    $flat = array_merge($flat, $groups[$catName]);
                    unset($groups[$catName]);
                }
            }
        }

        foreach ($groups as $catName => $catItems) {
            if ($catName !== '') {
                $flat[] = new ListMenuCategory($catName);
            }
            $flat = array_merge($flat, $catItems);
        }

        $this->items->setAll($flat);

        foreach ($flat as $i => $item) {
            if (!($item instanceof ListMenuCategory)) {
                $this->selectedIndex = $i;
                break;
            }
        }
    }

    /**
     * @return boolean
     */
    public function isNameThin()
    {
        return $this->nameThin;
    }

    /**
     * @param boolean $nameThin
     */
    public function setNameThin($nameThin)
    {
        $this->nameThin = $nameThin;
    }

    /**
     * @return bool
     */
    public function isThin()
    {
        return $this->thin;
    }

    /**
     * @param bool $thin
     */
    public function setThin($thin)
    {
        $this->thin = $thin;
        $this->fixedCellSize = $thin ? 40 : 50;
    }

    public function getDescriptionOfItem(MenuViewable $item)
    {
        return $this->descriptionGetter
            ? call_user_func($this->descriptionGetter, $item)
            : $item->getDescription();
    }

    public function getIconOfItem(MenuViewable $item)
    {
        return $this->iconGetter
            ? call_user_func($this->iconGetter, $item)
            : $item->getIcon();
    }

    public function getNameOfItem(MenuViewable $item)
    {
        return $this->nameGetter
            ? call_user_func($this->nameGetter, $item)
            : $item->getName();
    }

    /**
     * @param callable $descriptionGetter
     */
    public function setDescriptionGetter(callable $descriptionGetter)
    {
        $this->descriptionGetter = $descriptionGetter;
    }

    /**
     * @param callable $nameGetter
     */
    public function setNameGetter(callable $nameGetter)
    {
        $this->nameGetter = $nameGetter;
    }

    /**
     * @param callable $iconGetter
     */
    public function setIconGetter(callable $iconGetter)
    {
        $this->iconGetter = $iconGetter;
    }

    protected function cellRender(UXListCell $view, $page)
    {
        $view->text = null;

        if ($page instanceof ListMenuCategory) {
            $label = new UXLabel(str::upper($page->name));
            $label->style = '-fx-font-weight: bold; -fx-font-size: 10; -fx-text-fill: #888888; -fx-padding: 6 5 2 5;';
            $view->text = null;
            $view->graphic = $label;
            return;
        }

        $titleName = new UXLabel($this->getNameOfItem($page));
        $titleName->classes->add('sprk-list-menu-title');

        if ($this->isNameThin()) {
            $titleName->style = '-fx-font-weight: normal;';
        }

        $titleName->style .= UiUtils::fontSizeStyle() . ";";

        $titleDescription = new UXLabel($this->getDescriptionOfItem($page));
        $titleDescription->classes->add('sprk-list-menu-description');
        $titleDescription->style .= UiUtils::fontSizeStyle() . ";";

        $box = new UXHBox([$titleName]);
        $box->spacing = 0;

        $title = new UXVBox([$box, $titleDescription]);
        $title->spacing = 0;

        $list = [];

        $icon = $this->getIconOfItem($page);

        if ($icon) {
            $list[] = Ide::get()->getImage($icon);
        }

        $list[] = $title;

        UXHBox::setHgrow($title, 'ALWAYS');

        if ($page->getMenuCount() >= 0) {
            $label = new UXLabel($page->getMenuCount());
            $label->classes->add('sprk-list-menu-count');
            $label->style = UiUtils::fontSizeStyle();

            $list[] = $label;
        }

        $line = new UXHBox($list);

        $line->spacing = 7;
        $line->padding = 5;
        $line->alignment = 'CENTER_LEFT';

        $view->text = null;
        $view->graphic = $line;
    }

    public function clear()
    {
        $this->items->clear();
    }

    public function add(MenuViewable $page)
    {
        $this->items->add($page);
    }

    public function refresh()
    {
        $selected = $this->selectedIndexes;

        $this->items->setAll(arr::of($this->items));

        $this->selectedIndexes = $selected;

        $this->opacity = 0;
        Animation::fadeTo($this, 200, 1.0);
    }
}
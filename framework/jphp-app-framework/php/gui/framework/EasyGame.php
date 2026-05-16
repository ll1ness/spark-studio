<?php
namespace php\gui\framework;

use std, gui, framework, app;
use php\gui\UXNode;
use php\gui\event\UXMouseEvent;
use php\gui\UXTimer;

class EasyGame {
    /**
     * Константы для различных типов выравнивания
     */
    const ALIGN_CENTER = 'center';
    const ALIGN_TOP_LEFT = 'top_left';
    const ALIGN_TOP_RIGHT = 'top_right';
    const ALIGN_BOTTOM_LEFT = 'bottom_left';
    const ALIGN_BOTTOM_RIGHT = 'bottom_right';
    const ALIGN_CENTER_TOP = 'center_top';

    /**
     * Метод для поворота объекта к указателю мыши
     *
     * @param UXNode $node Объект, который нужно повернуть
     * @param UXMouseEvent $event Событие мыши
     */
    public function rotateToMouse(UXNode $node, UXMouseEvent $event) {
        $dx = $event->x - $node->x;
        $dy = $event->y - $node->y;
        $angle = atan2($dy, $dx) * 180 / pi();
        $node->rotate = $angle;
    }

    /**
     * Метод для перемещения объекта к указателю мыши с заданным выравниванием
     *
     * @param UXNode $node Объект, который нужно переместить
     * @param UXMouseEvent $event Событие мыши
     * @param string $alignment Выравнивание (по умолчанию 'center')
     */
    public function moveToMouse(UXNode $node, UXMouseEvent $event, $alignment = self::ALIGN_CENTER) {
        switch ($alignment) {
            case self::ALIGN_CENTER:
                $node->x = $event->x - $node->width / 2;
                $node->y = $event->y - $node->height / 2;
                break;
            case self::ALIGN_TOP_LEFT:
                $node->x = $event->x;
                $node->y = $event->y;
                break;
            case self::ALIGN_TOP_RIGHT:
                $node->x = $event->x - $node->width;
                $node->y = $event->y;
                break;
            case self::ALIGN_BOTTOM_LEFT:
                $node->x = $event->x;
                $node->y = $event->y - $node->height;
                break;
            case self::ALIGN_BOTTOM_RIGHT:
                $node->x = $event->x - $node->width;
                $node->y = $event->y - $node->height;
                break;
            case self::ALIGN_CENTER_TOP:
                $node->x = $event->x - $node->width / 2;
                $node->y = $event->y;
                break;
            default:
                throw new \InvalidArgumentException("Invalid alignment: $alignment");
        }
    }
}
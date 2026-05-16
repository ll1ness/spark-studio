<?php
namespace php\gui\framework;

use std, gui, framework, app;
use php\gui\UXNode;
use php\gui\animation\UXAnimationTimer;
use action\Animation;
use php\gui\paint\UXColor;
use php\gui\UXColor;

/**
 * Framework который добавляет новые анимации.
 */
class AnimationKitx
{   
    /**
 * --RU--
 * Смещение объекта по x
 */
function displaceX($node, $b, $c, $d, $type = false, callable $callback = null){
    // Если тип равен true, то мы вычитаем текущую позицию x узла из $c
    if ($type == true) $c = $c - $node->x;
    // Преобразуем продолжительность анимации в кадры
    $d = $d/UXAnimationTimer::FRAME_INTERVAL_MS;
    // Создаем новый таймер анимации
    $timer = new UXAnimationTimer(function () use (&$timer, $node, $b, $c, $d, $callback){
        static $currentTime;
        // Если текущее время больше или равно продолжительности, останавливаем таймер
        if ($currentTime >= $d){ $timer->stop(); if ($callback) { $callback(); } }
        // Вычисляем новую позицию x узла
        $ease = $this->Ease($currentTime, $b, $c, $d);
        $node->x = round($ease, 1);
        // Увеличиваем текущее время
        $currentTime++; 
    });
    // Запускаем таймер
    $timer->start();
}

/**
 * --RU--
 * Смещение объекта по y
 */
function displaceY($node, $b, $c, $d, $type = false, callable $callback = null){
    // Если тип равен true, то мы вычитаем текущую позицию y узла из $c
    if ($type == true) $c = $c - $node->y;
    // Преобразуем продолжительность анимации в кадры
    $d = $d/UXAnimationTimer::FRAME_INTERVAL_MS;
    // Создаем новый таймер анимации
    $timer = new UXAnimationTimer(function () use (&$timer, $node, $b, $c, $d, $callback){
        static $currentTime;
        // Если текущее время больше или равно продолжительности, останавливаем таймер
        if ($currentTime >= $d){ $timer->stop(); if ($callback) { $callback(); } }
        // Вычисляем новую позицию y узла
        $ease = $this->Ease($currentTime, $b, $c, $d);
        $node->y = round($ease, 1);
        // Увеличиваем текущее время
        $currentTime++;  
    });
    // Запускаем таймер
    $timer->start();
}

/**
 * --RU--
 * Анимация цвета атрибута
 */
function animateColorAttribute($node, $attribute, $color, $duration, callable $callback = null){
    // Создаем объекты UXColor для начального и конечного цвета
    $startColor = UXColor::of($node->{$attribute});
    $endColor = UXColor::of($color);

    // Если endColor равен null, значит, цвет не является допустимым значением для UXColor
    if ($endColor === null) {
        throw new Exception("Цвет " . $color . " не является допустимым значением для UXColor.");
    }

    // Получаем RGB-значения начального и конечного цвета
    $startRgb = sscanf($startColor->webValue, "#%02x%02x%02x");
    $endRgb = sscanf($endColor->webValue, "#%02x%02x%02x");

    // Вычисляем количество кадров для анимации
    $frames = $duration / UXAnimationTimer::FRAME_INTERVAL_MS;

    // Создаем новый объект UXAnimationTimer
    $timer = new UXAnimationTimer(function () use (&$timer, $node, $attribute, $startRgb, $endRgb, $frames, $callback) {
        static $currentFrame = 0;

        // Если текущий кадр больше или равен общему количеству кадров, останавливаем анимацию
        if ($currentFrame >= $frames) {
            $timer->stop();
            if ($callback) {
                $callback();
            }
        }

        // Вычисляем новые значения RGB для цвета
        $r = $this->Ease($currentFrame, $startRgb[0], $endRgb[0] - $startRgb[0], $frames);
        $g = $this->Ease($currentFrame, $startRgb[1], $endRgb[1] - $startRgb[1], $frames);
        $b = $this->Ease($currentFrame, $startRgb[2], $endRgb[2] - $startRgb[2], $frames);

        // Применяем новый цвет к атрибуту объекта
        $node->{$attribute} = UXColor::of(sprintf("#%02x%02x%02x", $r, $g, $b));

        // Увеличиваем текущий кадр
        $currentFrame++;
    });

    // Запускаем анимацию
    $timer->start();
}

/**
 * --RU--
 * Изменение радиуса границы
 */
function changeBorderRadius($node, $b, $c, $d, callable $callback = null){
    $c = $c - $node->borderRadius;
    $d = $d/UXAnimationTimer::FRAME_INTERVAL_MS;
    $timer = new UXAnimationTimer(function () use (&$timer, $node, $b, $c, $d, $callback){
        static $currentTime;
        if ($currentTime >= $d){ $timer->stop(); if ($callback) { $callback(); } }
        $ease = $this->Ease($currentTime, $b, $c, $d);
        $node->borderRadius = round($ease, 1);
        //var_dump($currentTime." -radius- ".$node->borderRadius); 
        $currentTime++; 
    });
    $timer->start();
}

/**
 * --RU--
 * Изменение прозрачности
 */
function changeOpacity($node, $b, $c, $d, callable $callback = null){
    $c = $c - $node->opacity;
    $d = $d/UXAnimationTimer::FRAME_INTERVAL_MS;
    $timer = new UXAnimationTimer(function () use (&$timer, $node, $b, $c, $d, $callback){
        static $currentTime;
        if ($currentTime >= $d){ $timer->stop(); if ($callback) { $callback(); } }
        $ease = $this->Ease($currentTime, $b, $c, $d);
        $node->opacity = round($ease, 2);
        //var_dump($currentTime." -opacity- ".$node->opacity); 
        $currentTime++; 
    });
    $timer->start();
}

/**
 * --RU--
 * Изменение масштаба
 */
function changeScale($node, $b, $n, $e,  $c, $d, callable $callback = null){
    $e = $e - $node->scaleX;
    $c = $c - $node->scaleY;
    $d = $d/UXAnimationTimer::FRAME_INTERVAL_MS;
    $timer = new UXAnimationTimer(function () use (&$timer, $node, $b, $n, $e, $c, $d, $callback){
        static $currentTime;
        if ($currentTime >= $d){ $timer->stop(); if ($callback) { $callback(); } }
        $easeX = $this->Ease($currentTime, $b, $e, $d);
        $easeY = $this->Ease($currentTime, $n, $c, $d);
        $node->scale = [round($easeX, 2), round($easeY, 2)];
        $currentTime++;  
        //var_dump($currentTime." -x- -y- ".$node->scale[0].' --- '.$node->scale[1]); 
    });
    $timer->start();
}

/**
 * --RU--
 * Запуск анимации RGB
 */
public function RGBAnimationStart($Node, $Quality, $increment = 1){
    $this->Status = true;
    $this->Node = $Node;
    $this->Quality = $Quality;
    $this->increment = $increment;
    $this->r = $this->g = $this->b = 0;
    $this->RGBAnimationAction();
}

private function RGBAnimationAction(){
    $this->timer = new UXAnimationTimer(function () {
        if ($this->isStart() == true){ 
            $Quality = $this->Quality;
            $Node = $this->Node;

            if ($this->r < 255 && $this->g == 0 && $this->b == 0) {
                $this->r = min(255, $this->r + $this->increment);
            } elseif ($this->r >= 255 && $this->g < 255 && $this->b == 0) {
                $this->g = min(255, $this->g + $this->increment);
            } elseif ($this->r >= 255 && $this->g >= 255 && $this->b < 255) {
                $this->b = min(255, $this->b + $this->increment);
            } elseif ($this->r > 0 && $this->g >= 255 && $this->b >= 255) {
                $this->r = max(0, $this->r - $this->increment);
            } elseif ($this->r <= 0 && $this->g > 0 && $this->b >= 255) {
                $this->g = max(0, $this->g - $this->increment);
            } elseif ($this->r <= 0 && $this->g <= 0 && $this->b > 0) {
                $this->b = max(0, $this->b - $this->increment);
            }

            if (isset($Node->$Quality)) {
                $Node->$Quality = UXColor::rgb($this->r, $this->g, $this->b);
            } else {
                $this->StopAnimationRGB();
            }
        } else {
            $this->timer->stop(); 
        }
    });

    $this->timer->start();  
}

public function isStart(){
    return $this->Status;
}

public function StopAnimationRGB(){
    $this->Status = false;
    return true;
}

      function resize($node, $b, $n, $e,  $c, $d, callable $callback = null){
        $e = $e - $node->width;
        $c = $c - $node->height;
        $d = $d/UXAnimationTimer::FRAME_INTERVAL_MS;
        $timer = new UXAnimationTimer(function () use (&$timer, $node, $b, $n, $e, $c, $d, $callback){
            static $currentTime;
            if ($currentTime >= $d){ $timer->stop(); if ($callback) { $callback(); } }
            $easeW = $this->Ease($currentTime, $b, $e, $d);
            $easeH = $this->Ease($currentTime, $n, $c, $d);
            $node->size = [round($easeW, 1), round($easeH, 1)];
            $currentTime++;  
            //var_dump($currentTime." -w---h- ".$node->size[0].' --- '.$node->size[1]); 
        });
        $timer->start();
    }
    function Ease($t, $b, $c, $d){
        $t /= $d/2;
        if ($t < 1) return $c/2*$t*$t + $b;
        $t--;
        return -$c/2 * ($t*($t-2) - 1) + $b;
    }    
}
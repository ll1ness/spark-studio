<?php
namespace action;

use php\framework\Logger;
use php\gui\animation\UXAnimationTimer;
use php\gui\animation\UXFadeAnimation;
use php\gui\animation\UXPathAnimation;
use php\gui\framework\behaviour\PositionableBehaviour;
use php\gui\framework\Instances;
use php\gui\framework\ObjectGroup;
use php\gui\framework\ScriptEvent;
use php\gui\UXNode;
use php\gui\UXWindow;
use php\lang\IllegalArgumentException;
use php\lib\reflect;
use script\TimerScript;
use timer\AccurateTimer;

/**
 * Class Animation
 * @package action
 *
 * @packages framework
 */
class Animation
{
    /**
     * Fade animation — uses JavaFX FadeTransition for smooth 60fps.
     *
     * @param $object
     * @param int $duration in millis
     * @param float $value target opacity (0.0 – 1.0)
     * @param callable|null $callback
     * @return UXAnimationTimer|UXFadeAnimation|null
     */
    static function fadeTo($object, $duration, $value, callable $callback = null)
    {
        if ($object instanceof Instances) {
            $cnt = sizeof($object);

            $done = function () use (&$cnt, $callback) {
                $cnt--;

                if ($cnt <= 0) {
                    $callback();
                }
            };

            $object->flow()->map(function () use ($object, $duration, $value, $done) {
                Animation::fadeTo($object, $duration, $value, $done);
            });
            return null;
        }

        // Use native JavaFX FadeTransition for smooth 60fps animation.
        $from = (double) $object->opacity;

        try {
            $anim = new UXFadeAnimation($duration, $object);
            $anim->fromValue = $from;
            $anim->toValue = (double) $value;
            $anim->on('finish', function () use ($object, $value, $callback) {
                $object->opacity = (double) $value;
                if ($callback) {
                    $callback();
                }
            });
            $anim->play();
            return $anim;
        } catch (\Throwable $e) {
            // Fallback: timer-based animation
        }

        $diff = $value - $object->opacity;
        $interval = max(10, (int) ($duration / 60));
        $steps = max(1, (int) ($duration / $interval));
        $step = $diff / $steps;

        $timer = new UXAnimationTimer(function () use ($object, $step, $value, $callback, &$steps) {
            $opacity = $object->opacity + $step;

            if ($opacity > 1) {
                $opacity = 1;
            }

            $object->opacity = $opacity < 0 ? 0 : $opacity;

            $steps--;

            if ($steps <= 0) {
                $object->opacity = (double) $value;

                if ($callback) {
                    $callback();
                }

                return true;
            }

            return false;
        });

        $timer->start();
        return $timer;
    }

    static function fadeIn($object, $duration, callable $callback = null)
    {
        return self::fadeTo($object, $duration, 1.0, $callback);
    }

    static function fadeOut($object, $duration, callable $callback = null)
    {
        return self::fadeTo($object, $duration, 0.0, $callback);
    }

    /**
     * Scale animation — time-based interpolation for smooth 60fps.
     *
     * @param UXNode $object
     * @param int $duration in millis
     * @param double $value
     * @param callable $callback
     * @return UXAnimationTimer|null
     */
    static function scaleTo(UXNode $object, $duration, $value, callable $callback = null)
    {
        static::stopScale($object);

        if ($object instanceof Instances) {
            $cnt = sizeof($object);

            $done = function () use (&$cnt, $callback) {
                $cnt--;

                if ($cnt <= 0) {
                    $callback();
                }
            };

            $object->flow()->map(function () use ($object, $duration, $value, $done) {
                Animation::scaleTo($object, $duration, $value, $done);
            });

            return null;
        }

        $startScale = (double) $object->scaleX;
        $diff = $value - $startScale;
        $startTime = microtime(true) * 1000.0;

        $timer = new UXAnimationTimer(function () use ($object, $startScale, $diff, $duration, $value, $callback, $startTime) {
            $elapsed = (microtime(true) * 1000.0) - $startTime;
            $progress = $elapsed / (double) $duration;

            if ($progress >= 1.0) {
                $object->scaleX = $object->scaleY = (double) $value;

                if ($callback) {
                    $callback();
                }

                return true; // stop timer
            }

            $currentValue = $startScale + $diff * $progress;
            $object->scaleX = $currentValue;
            $object->scaleY = $currentValue;

            return false; // continue timer
        });

        $object->data(Animation::class . "#scaleTo", $timer);
        $timer->start();
        return $timer;
    }

    static function stopScale(UXNode $object)
    {
        $timer = $object->data(Animation::class . "#scaleTo");

        if ($timer instanceof UXAnimationTimer) {
            $timer->stop();
        }
    }

    /**
     * @param UXNode|UXWindow $object
     */
    static function stopMove($object)
    {
        $timer = $object->data(Animation::class . "#moveTo");

        if ($timer instanceof UXAnimationTimer) {
            $timer->stop();
        }
    }

    /**
     * Displace animation — time-based interpolation for smooth 60fps.
     *
     * @param UXNode|UXWindow $object
     * @param int $duration
     * @param double $x
     * @param double $y
     * @param callable $callback
     * @return UXAnimationTimer|array|null
     */
    static function displace($object, $duration, $x, $y, callable $callback = null)
    {
        return self::moveTo($object, $duration, $object->x + $x, $object->y + $y, $callback);
    }

    /**
     * Move to point animation — time-based interpolation for smooth 60fps.
     *
     * @param UXNode|UXWindow $object
     * @param int $duration
     * @param double $x
     * @param double $y
     * @param callable|null $callback
     * @return array|null|UXAnimationTimer
     */
    static function moveTo($object, $duration, $x, $y, callable $callback = null)
    {
        if ($object instanceof Instances) {
            $cnt = sizeof($object);

            $done = function () use (&$cnt, $callback) {
                $cnt--;

                if ($cnt <= 0) {
                    $callback();
                }
            };

            $result = [];

            $object->flow()->map(function () use ($object, $duration, $x, $y, $done, &$result) {
                $result[] = Animation::moveTo($object, $duration, $x, $y, $done);
            });

            return $result;
        }

        if ($object instanceof UXWindow) {
            if (!$object->visible) {
                if ($callback) {
                    AccurateTimer::executeAfter($duration, $callback);
                }

                return null;
            }
        }

        if ($object instanceof UXNode || $object instanceof UXWindow || $object instanceof PositionableBehaviour) {
            $startX = (double) $object->x;
            $startY = (double) $object->y;
            $diffX = $x - $startX;
            $diffY = $y - $startY;
            $startTime = microtime(true) * 1000.0;

            $timer = new UXAnimationTimer(function () use ($object, $startX, $startY, $diffX, $diffY, $duration, $x, $y, $callback, $startTime) {
                $elapsed = (microtime(true) * 1000.0) - $startTime;
                $progress = $elapsed / (double) $duration;

                if ($progress >= 1.0) {
                    $object->position = [round($x), round($y)];
                    $object->data(Animation::class . "#moveTo", null);

                    if ($callback) {
                        $callback();
                    }

                    return true; // stop timer
                }

                $object->x = $startX + $diffX * $progress;
                $object->y = $startY + $diffY * $progress;

                return false; // continue timer
            });

            $object->data(Animation::class . "#moveTo", $timer);
            $timer->start();

            return $timer;
        }

        Logger::warn("Cannot animate object(" . reflect::typeOf($object) . "), it's not supported for this type");
    }
}

<?php
namespace ide\forms;

use ide\Ide;
use ide\Logger;
use ide\systems\SplashTipSystem;
use php\gui\event\UXEvent;
use php\gui\framework\AbstractForm;
use php\gui\UXImageArea;
use php\gui\UXImage;
use php\lib\str;
use php\time\Time;
use php\io\Stream;
use ide\ui\Notifications;
use php\lib\fs;
use php\io\File;

/**
 * @property UXLabel $version
 * @property UXLabel $tip
 */
class SplashForm extends AbstractIdeForm
{
    protected function init()
    {
        Logger::debug("Init form ...");

        $this->centerOnScreen();

        $this->version->text = $this->_app->getVersion();

        $versionCode = $this->_app->getConfig()->get('app.versionCode');
        if ($versionCode) {
            $this->versionCode->text = str::upperFirst($versionCode);

            $now = Time::now()->toString('dd-MM');
            $specialCode = 'res://.data/img/code/special/' . $now . '.png';

            if (Stream::exists($specialCode)) {
                $versionCode = "special/$now";
            }

            $codeImg = new UXImageArea(new UXImage('res://.data/img/code/' . $versionCode . '.png'));
            $codeImg->stretch = true;
            $codeImg->smartStretch = true;
            $codeImg->size = [64, 64];
            $codeImg->position = [690 - 64 - 14, 14];

            $this->add($codeImg);
        }

        $this->adjustMemorySettings();

        waitAsync(3000, function() {
            $this->hide();

            uiLater(function() {
                Notifications::success("Мастер Обновлений", "У вас установлена последняя версия Spark Studio " . Ide::get()->getConfig()->get('app.version'));

                $updateServicePath = "./update-service.jar";
                if (fs::isFile($updateServicePath)) {
                    $file = new File($updateServicePath);
                    if ($file->delete()) {

                    }
                }
            });
        });
    }

    protected function adjustMemorySettings()
    {
        $totalMemoryBytes = 0;

        if (fs::isFile('/proc/meminfo')) {
            $output = file_get_contents('/proc/meminfo');

            foreach (explode("\n", $output) as $line) {
                $line = str::trim($line);

                if (str::startsWith($line, 'MemTotal:')) {
                    $parts = explode(' ', $line);
                    $kb = 0;

                    foreach ($parts as $part) {
                        $part = str::trim($part);

                        if (is_numeric($part)) {
                            $kb = (int)$part;
                        }
                    }

                    $totalMemoryBytes = $kb * 1024;
                    break;
                }
            }
        } else {
            $output = shell_exec('wmic memorychip get capacity');
            $lines = explode("\n", trim($output));

            for ($i = 1; $i < count($lines); $i++) {
                $line = trim($lines[$i]);
                if (is_numeric($line)) {
                    $totalMemoryBytes += (int)$line;
                }
            }
        }

        $totalMemoryGB = (int)($totalMemoryBytes / (1024 ** 3));

        $initialHeapSize = '128M';
        $maxHeapSize = '1024M'; 

        if ($totalMemoryGB >= 16) {
            $initialHeapSize = '512M';
            $maxHeapSize = '4096M';
        } elseif ($totalMemoryGB >= 8) {
            $initialHeapSize = '512M';
            $maxHeapSize = '2048M';
        } elseif ($totalMemoryGB >= 4) {
            $initialHeapSize = '256M';
            $maxHeapSize = '1024M';
        } elseif ($totalMemoryGB >= 2) {
            $initialHeapSize = '256M';
            $maxHeapSize = '512M';
        }

        $iniFilePath = './Spark.l4j.ini';

        if (fs::exists($iniFilePath)) {
            $content = Stream::getContents($iniFilePath);

            $lines = explode("\n", $content);
            foreach ($lines as &$line) {
                if (str::contains($line, '-Xms')) {
                    $line = '-Xms' . $initialHeapSize;
                } elseif (str::contains($line, '-Xmx')) {
                    $line = '-Xmx' . $maxHeapSize;
                }
            }
            
            $newContent = implode("\n", $lines);
            Stream::putContents($iniFilePath, $newContent);
        }
    }

    /**
     * @param UXEvent $e
     * @event tip.click
     */
    public function doTipClick(UXEvent $e)
    {
        $this->tip->text = SplashTipSystem::get(Ide::get()->getLanguage()->getCode());
        $e->consume();
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $this->tip->text = SplashTipSystem::get(Ide::get()->getLanguage()->getCode());

        if (Ide::get()->isDevelopment() && Ide::get()->isWindows()) {
            $this->opacity = ($this->opacity > 0.9) ? 0.5 : 1;
        }

        uiLater(function () {
            $this->toFront();
        });
    }
}
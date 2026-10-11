<?php
namespace ide\systems;


use ide\IdeClassLoader;
use php\io\File;
use php\lang\System;
use php\lib\fs;
use php\lib\str;
use php\time\Time;

class IdeSystem
{
    /**
     * @return string
     */
    static function getOs()
    {
        return str::lower(System::getProperty('os.name'));
    }

    /**
     * @return string
     */
    static function getMode()
    {
        $mode = 'prod';
        $env = System::getEnv();

        if ($value = System::getProperty('spark.mode')) {
            $mode = $value;
        } else if (isset($env['DEVELNEXT_MODE'])) {
            $mode = $env['DEVELNEXT_MODE'];
        }

        return $mode;
    }

    /**
     * @return bool
     */
    static function isDevelopment()
    {
        return Str::equalsIgnoreCase(self::getMode(), 'dev');
    }

    /**
     * @return string
     */
    static function getOwnLibVersion()
    {
        $hash = ["version"];

        foreach (self::getOwnLibDirectories() as $directory) {
            foreach ($directory->findFiles() as $file) {
                if (fs::ext($file) == 'jar') {
                    $hash[] = $file->hash('MD5');
                }
            }
        }

        return str::join($hash, "+");
    }

    /**
     * @return File[]
     */
    static function getOwnLibDirectories()
    {
        $result = [self::getOwnFile("lib/")];

        if (self::isDevelopment()) {
            $result[] = self::getOwnFile('build/install/spark/lib');
        }

        return $result;
    }

    /**
     * @param string $path
     *
     * @return File
     */
    static function getOwnFile($path)
    {
        // Корень приложения задаёт лаунчер (spark.path). Раньше здесь было
        // жёстко "./" — это работало, только пока рабочий каталог процесса
        // совпадал с распакованным app-каталогом. При запуске из exe рабочий
        // каталог другой, поэтому языки/иконка и т.п. не находились.
        $home = System::getProperty('spark.path', "./");

        return File::of("$home/$path");
    }

    /**
     * Каталог данных пользователя (spark.data) — всё записываемое хранится
     * здесь, а не рядом с программой и не в каталоге установки.
     *
     * Приоритет:
     *  1. системное свойство spark.data (задаётся лаунчером),
     *  2. Windows: %APPDATA%/SparkStudio,
     *  3. macOS:   ~/Library/Application Support/SparkStudio,
     *  4. Linux:   $XDG_DATA_HOME/spark-studio или ~/.local/share/spark-studio.
     *
     * @return File
     */
    static function getDataDirectory()
    {
        static $dataDir = null;

        if ($dataDir) {
            return $dataDir;
        }

        $home = System::getProperty('user.home');

        if ($value = System::getProperty('spark.data')) {
            $dataDir = File::of($value);
        } else {
            $os = self::getOs();
            $env = System::getEnv();

            if (str::contains($os, 'win')) {
                $base = $env['APPDATA'] ? $env['APPDATA'] : "$home/.SparkStudio";
                $dataDir = File::of("$base/SparkStudio");
            } else if (str::contains($os, 'mac')) {
                $dataDir = File::of("$home/Library/Application Support/SparkStudio");
            } else {
                $base = $env['XDG_DATA_HOME'] ? $env['XDG_DATA_HOME'] : "$home/.local/share";
                $dataDir = File::of("$base/spark-studio");
            }
        }

        if (!$dataDir->isDirectory()) {
            $dataDir->mkdirs();
        }

        return $dataDir = File::of($dataDir->getCanonicalPath());
    }

    static function getFile($path)
    {
        $ideHome = self::getDataDirectory();

        return File::of("$ideHome/$path");
    }

    /**
     * @return File
     */
    static function getByteCodeCacheDir()
    {
        static $byteCodeCacheDir;

        if (!$byteCodeCacheDir) {
            $dir = IdeSystem::getFile("cache/bytecode_v" . IdeClassLoader::VERSION);

            if (!$dir->isDirectory()) {
                return $dir;
            }

            $time = Time::millis();

            $index = 1;

            while (true) {
                $lockFile = new File($dir, "/ide.lock");

                if ($lockFile->exists()) {
                    $timestamp = (int) str::trim(fs::get($lockFile));

                    $diff = $time - $timestamp;

                    if ($diff < 15 * 1000) {
                        $index++;

                        $dir = self::getFile("cache/bytecode_v" . IdeClassLoader::VERSION . "@$index");

                        if (!$dir->isDirectory()) {
                            break;
                        }
                    } else {
                        fs::delete($lockFile);
                        break;
                    }
                } else {
                    break;
                }
            }

            return $byteCodeCacheDir = $dir;
        } else {
            return $byteCodeCacheDir;
        }
    }

    /**
     * Очищает от кэша IDE.
     * @param $path
     */
    static function clearCache($path)
    {
        $file = self::getFile("cache/$path");

        if ($file->isDirectory()) {
            fs::clean($file);
        }

        fs::delete($file);
    }

    /**
     * @param string $version
     * @return array
     */
    static function getVersionInfo($version)
    {
        list($number, $type) = str::split($version, ' ');

        $numbers = str::split($number, '.');

        if (!$type) {
            $type = 'stable';
            $major = (int) $numbers[0];
            $minor = (int) $numbers[1];
            $patch = (int) $numbers[2];
        } else {
            $type = str::split($version, '-')[0];
            $major = (int) $numbers[0];
            $minor = (int) $numbers[1];
            $patch = (int) $type[1];
        }

        return [
            'type' => $type,
            'major' => $major,
            'minor' => $minor,
            'patch' => $patch
        ];
    }

    /**
     * @var IdeClassLoader
     */
    protected static $loader;

    public static function setLoader($loader)
    {
        self::$loader = $loader;
    }

    public static function getLoader()
    {
        return self::$loader;
    }
}
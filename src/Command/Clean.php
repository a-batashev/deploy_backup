<?php

namespace App\Command;

/**
 * Removes content of the directory
 */
class Clean extends Command
{
    /**
     * Run command
     */
    public static function run()
    {
        $sitePath = self::$config['sitePath'];

        self::checkDir($sitePath);

        self::cleanDir($sitePath);

        echo 'Cleaning complete', PHP_EOL;
    }

    /**
     * Check directory and remove children files/directories
     *
     * @param string $dir
     * @return void
     */
    public static function checkDir(string $dir)
    {
        if (!is_readable($dir)) {
            throw new \Exception("Directory isn't readable: '{$dir}'.");
        }

        if (!is_writable($dir)) {
            throw new \Exception("Directory isn't writable: '{$dir}'.");
        }
    }

    /**
     * Remove children of the directory
     *
     * @param string $dir
     * @return void
     */
    public static function cleanDir(string $dir)
    {
        self::rrmdir($dir, self::$args->isDryRun());

        if (!file_exists($dir)) {
            mkdir($dir, 0775);
        }
    }

    /**
     * Remove recursively content of a directory
     *
     * @param string $dir
     * @param boolean $dryRun    Don't clean, only print names of files/dirs
     * @param boolean $deleteThisDir    Remove self?
     * @return void
     */
    private static function rrmdir(string $dir, bool $dryRun = false, bool $deleteThisDir = false)
    {
        if (!is_readable($dir) || !is_writable($dir)) {
            echo "Can't remove {$dir}", PHP_EOL;

            return;
        }

        $scan = scandir($dir);

        if ($scan === false) {
            echo "Can't access to directory {$dir}", PHP_EOL;

            return;
        }

        $files = array_diff(
            $scan,
            ['.', '..']
        );

        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;

            if (!is_readable($path)) {
                echo "Can't access to {$path}", PHP_EOL;
                continue;
            }

            if (is_dir($path)) {
                self::rrmdir($path, $dryRun, true);
            } else {
                if ($dryRun) {
                    echo "unlink {$path}", PHP_EOL;
                } else {
                    unlink($path);
                }
            }
        }

        if ($deleteThisDir) {
            if ($dryRun) {
                echo "rmdir {$dir}", PHP_EOL;
            } else {
                rmdir($dir);
            }
        }
    }
}

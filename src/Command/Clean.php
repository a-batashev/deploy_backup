<?php

namespace App\Command;

use App\ArgumentsProcessor;
use App\Config\Config;

/**
 * Removes content of the directory
 */
class Clean extends Command
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $config = Config::getInstance()->getConfigByPreset();

        self::checkSitePath($config);

        self::checkDir($config['sitePath']);

        self::cleanDir($config['sitePath']);

        echo 'Clean', PHP_EOL;
    }

    /**
     * Check sitepath at configuration file
     *
     * @return void
     */
    private static function checkSitePath(array $config)
    {
        if (!isset($config['sitePath'])) {
            throw new \Exception("Configuration option 'sitePath' is empty.");
        }

        if (!file_exists($config['sitePath'])) {
            throw new \Exception("Incorrect path to site directory: '{$config['sitePath']}'.");
        }
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
        $dryRun = ArgumentsProcessor::getInstance()->getOption('dry-run') ?? false;

        self::rrmdir($dir, $dryRun);

        if (!file_exists($dir)) {
            mkdir($dir, 0775);
        }
    }

    /**
     * Remove recursively content of a directory
     *
     * @param string $dir
     * @param boolean $dryRun   Don't remove, only print
     * @param boolean $deleteThisDir    Remove self?
     * @return void
     */
    private static function rrmdir(string $dir, bool $dryRun = false, bool $deleteThisDir = false)
    {
        if (!is_readable($dir) || !is_writable($dir)) {
            echo "can't remove {$dir}", PHP_EOL;

            return;
        }

        $scan = scandir($dir);

        if ($scan === false) {
            echo "can't access to directory {$dir}", PHP_EOL;

            return;
        }

        $files = array_diff(
            $scan,
            ['.', '..']
        );

        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;

            if (!is_writable($path) || !is_readable($path)) {
                echo "can't access to {$path}", PHP_EOL;
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

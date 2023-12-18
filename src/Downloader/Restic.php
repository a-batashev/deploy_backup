<?php

namespace App\Downloader;

use App\Config;
use App\ConsoleInput;

/**
 * Restic
 */
class Restic implements DownloaderInterface
{
    /**
     * Get files from Restic's repository
     */
    public static function get(): void
    {
        $options = ConsoleInput::getInstance()->getOptions();
        $cfg = Config::getInstance()->getConfigByPreset();

        $transportOptions = (array) $cfg['transport']['options'];

        $cmd = 'restic restore latest';
        foreach ($transportOptions as $key => $value) {
            $cmd .= " --{$key}";

            if ($value != '') {
                $cmd .= '=' . escapeshellarg($value);
            }
        }

        if ($options['dry-run']) {
            echo $cmd, PHP_EOL;
        } else {
            exec($cmd, $output, $return);

            if ($return) {
                if (!$options['quiet']) {
                    echo "Can't get files (code: {$return}).";
                }
            }
        }
    }
}

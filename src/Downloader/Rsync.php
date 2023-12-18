<?php

namespace App\Downloader;

use App\Config;

/**
 * Rsync
 */
class Rsync implements DownloaderInterface
{
    /**
     * Get files by rsync
     *
     * @throws \Exception
     */
    public static function get(): void
    {
        $options = ConsoleInput::getInstance()->getOptions();
        $cfg = Config::getInstance()->getConfigByPreset();

        $transportOptions = (array) $cfg['transport']['options'];

        $cmd = 'rsync';
        foreach ($transportOptions as $key => $value) {
            $cmd .= " --{$key}";

            $value = strval($value);

            if ($value !== '') {
                $cmd .= '=' . escapeshellarg($value);
            }
        }

        if ($cfg['transport']['from'] == '' || $cfg['sitePath'] == '') {
            throw new \Exception("Missed option [transport][from] or [sitePath]");
        }

        $cmd .= " {$cfg['transport']['from']} {$cfg['sitePath']}";

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

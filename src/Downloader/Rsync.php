<?php

namespace App\Downloader;

use App\ArgumentsProcessor;
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
     * @return void
     */
    public static function get()
    {
        $args = ArgumentsProcessor::getInstance();

        $config = Config::getInstance()->getConfigByPreset();

        $sitePath = $config['sitePath'];

        $tag = $args->getArgument('preset');

        $options = $config['transport']['options'];

        $cmd = 'rsync';
        foreach ($options as $key => $value) {
            $cmd .= " --{$key}";

            if (strlen($value)) {
                $cmd .= '=' . escapeshellarg($value);
            }
        }

        if ($config['transport']['from'] == '' || $config['sitePath'] == '') {
            throw new \Exception("Missed option [transport][from] or [sitePath]");
        }

        $cmd .= " {$config['transport']['from']} {$config['sitePath']}";

        if ($args->isDryRun()) {
            echo $cmd, PHP_EOL;
        } else {
            exec($cmd, $output, $return);

            if ($return) {
                if (!$args->isQuiet()) {
                    echo "Can't get files (code: {$return}).";
                }
                // throw new \Exception("Can't get files.");
            }
        }
    }
}

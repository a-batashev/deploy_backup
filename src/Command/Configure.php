<?php

namespace App\Command;

/**
 * Set configuration for a development copy
 */
class Configure extends Command
{
    /**
     * Run command
     *
     * @return void
     */
    public static function run()
    {
        $confDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Configurator';
        $presetName = self::$args->getArgument('preset');

        $confClassFile = $confDir . DIRECTORY_SEPARATOR . ucfirst($presetName) . '.php';

        if (!is_file($confClassFile)) {
            throw new \Exception("Configuration settings file '{$confClassFile}' not found.");
        }

        require $confClassFile;

        if (!self::$args->isQuiet()) {
            echo 'Configuration setting complete', PHP_EOL;
        }
    }
}

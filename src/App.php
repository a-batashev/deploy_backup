<?php

namespace App;

use App\Config\Config;
use App\Command\Command;

/**
 * Main application
 */
class App
{
    /**
     * Construct
     *
     * @param array $rawArgs
     */
    public function __construct(array $rawArgs)
    {
        ArgumentsProcessor::getInstance()->process($rawArgs);
    }

    /**
     * Run the application
     *
     * @return void
     */
    public function run()
    {
        $args = ArgumentsProcessor::getInstance();

        $presetName = $args->getArgument('preset');
        $commandName = $args->getCommand();

        $config = Config::getInstance();

        $configFile = $this->makePathToConfig();

        // Parse the configuration file
        $config->parse($configFile);

        // Get configuration accordingly name of preset
        $config->setPreset($presetName);

        // Run command
        $command = new Command($commandName);
    }

    /**
     * Return path to the configuration file
     *
     * @return string
     */
    private function makePathToConfig(): string
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config.json';
    }
}

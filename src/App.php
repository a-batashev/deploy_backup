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
     * Command line arguments
     *
     * @var ArgumentsProcessor
     */
    private $args;

    /**
     * Construct
     *
     * @param array $rawArgs
     */
    public function __construct(array $rawArgs)
    {
        $this->args = new ArgumentsProcessor($rawArgs);
    }

    /**
     * Run the application
     *
     * @return void
     */
    public function run()
    {
        $args = $this->args;
        $presetName = $args->getArgument('preset');
        $commandName = $args->getCommand();

        $configFile = $this->makePathToConfig();

        $config = Config::getInstance();

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

<?php

namespace App\Command;

use App\Config;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'configure', description: 'Set configuration for a development copy')]
class Configure extends Command
{
    /** @inheritDoc */
    protected function executeChild(InputInterface $input, OutputInterface $output)
    {
        $preset = Config::getInstance()->getPreset();

        $confClassFile = dirname(__DIR__) . DIRECTORY_SEPARATOR
            . 'Configurator' . DIRECTORY_SEPARATOR
            . ucfirst($preset) . '.php';

        if (!is_file($confClassFile)) {
            $output->writeln("Configuration settings file '{$confClassFile}' not found.");
            return Command::FAILURE;
        }

        require $confClassFile;

        if (!$this->quiet) {
            $output->writeln('Configuration setting complete');
        }
    }
}

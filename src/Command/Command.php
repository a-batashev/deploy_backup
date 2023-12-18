<?php

namespace App\Command;

use App\Config;
use App\ConsoleInput;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Parent class for commands
 */
abstract class Command extends ConsoleCommand
{
    protected bool $dryRun = false;
    protected bool $quiet = false;
    protected array $cfg = [];

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->addArgument('preset', InputArgument::REQUIRED, 'Name of the preset specified in the configuration file')
            ->addOption('config', 'c', InputOption::VALUE_REQUIRED, 'Configuration file', 'config.yaml')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'Only print results');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Set input
        ConsoleInput::getInstance()->setInput($input);

        // Set options
        $this->dryRun = $input->getOption('dry-run');
        $this->quiet = $input->getOption('quiet');

        // Get configuration
        $this->cfg = Config::getInstance()
            ->setPreset($input->getArgument('preset'))
            ->setConfig($input->getOption('config'))
            ->parse();

        $this->executeChild($input, $output);

        return Command::SUCCESS;
    }

    /**
     * Run command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    abstract protected function executeChild(InputInterface $input, OutputInterface $output);
}

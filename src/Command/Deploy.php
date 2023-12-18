<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'deploy', description: 'Deploy backup')]
class Deploy extends Command
{
    /** @inheritDoc */
    protected function executeChild(InputInterface $input, OutputInterface $output)
    {
        $preset = $input->getArgument('preset');

        if (!$this->quiet) {
            $output->writeln("Deploy of '{$preset}' started");
        }

        $app = $this->getApplication();

        $commands = ['clean', 'download', 'database', 'configure'];
        foreach ($commands as $command) {
            $cmdInput = new ArrayInput([
                'command' => $command,
                'preset' => $preset,
                '--dry-run' => $input->getOption('dry-run'),
                '--quiet' => $input->getOption('quiet'),
                '--config' => $input->getOption('config'),
            ]);

            $app->doRun($cmdInput, $output);
        }

        if (!$this->quiet) {
            $output->writeln("Deploy of '{$preset}' is successfull");
        }
    }
}

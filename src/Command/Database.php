<?php

namespace App\Command;

use App\Config;
use App\Environment;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'database', description: 'Load database from the dump')]
class Database extends Command
{
    /** @inheritDoc */
    protected function executeChild(InputInterface $input, OutputInterface $output)
    {
        new Environment();

        $sshConfig = Config::getInstance()->getPreset();

        $dbName = $_ENV['database']['name'];

        $cmd = "restic -r sftp:bitrix@{$sshConfig}:/backup/restic-repo -p ~/scripts/restic.password dump --tag=mysql,{$dbName} latest {$dbName}.sql | mysql --login-path={$dbName} {$dbName}";

        if (!$this->quiet) {
            $output->writeln($cmd);
        }

        if (!$this->dryRun) {
            exec($cmd);
        }

        if (!$this->quiet) {
            $output->writeln('Database loading complete');
        }
    }
}

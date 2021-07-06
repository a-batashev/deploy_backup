<?php

namespace App\Command;

use App\Environment;
use App\Environment\DotEnv;

/**
 * Load database from a dump
 */
class Database extends Command
{
    /**
     * Run command
     *
     * @return void
     */
    public static function run()
    {
        new Environment();

        $args = self::$args;
        $sshConfig = $args->getArgument('preset');

        $dbName = $_ENV['database']['name'];

        $cmd = "restic -r sftp:bitrix@${sshConfig}:/backup/restic-repo -p ~/scripts/restic.password dump --tag=mysql,{$dbName} latest {$dbName}.sql | mysql --login-path={$dbName} {$dbName}";

        if (!$args->isQuiet()) {
            echo $cmd, PHP_EOL;
        }

        if (!$args->isDryRun()) {
            exec($cmd);
        }

        if (!self::$args->isQuiet()) {
            echo 'Database loading complete', PHP_EOL;
        }
    }
}

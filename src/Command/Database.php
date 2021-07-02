<?php

namespace App\Command;

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
        $envPath = self::$config['envPath'] ?? '';

        self::checkEnvPath($envPath);

        self::loadEnv($envPath);

        self::loadDump();

        if (!self::$args->isQuiet()) {
            echo 'Database loading complete', PHP_EOL;
        }
    }

    /**
     * Load settings from environment
     *
     * @param string $env
     * @return void
     */
    protected static function loadEnv(string $env)
    {
        $_ENV = require($env);
    }

    /**
     * Check environment path at configuration file
     *
     * @return void
     */
    protected static function checkEnvPath(string $envPath)
    {
        if ($envPath === '') {
            throw new \Exception("Configuration option 'envPath' is empty.");
        }

        if (!file_exists($envPath)) {
            throw new \Exception("Incorrect path to the environment file: '{$envPath}'.");
        }
    }

    /**
     * Load database dump
     *
     * @return void
     */
    protected static function loadDump()
    {
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
    }
}

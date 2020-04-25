<?php

namespace App\Command;

use Dotenv\Dotenv;

class Database extends Command
{
    /**
     * Load database from dump
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
     * Load settings from .env
     *
     * @param string $env
     * @return void
     */
    private static function loadEnv(string $env)
    {
        Dotenv::createImmutable($env)->load();
    }

    /**
     * Check environment path at configuration file
     *
     * @return void
     */
    private static function checkEnvPath(string $envPath)
    {
        if ($envPath === '') {
            throw new \Exception("Configuration option 'envPath' is empty.");
        }

        $envFile = "{$envPath}/.env";

        if (!file_exists($envFile)) {
            throw new \Exception("Incorrect path to the .env: '{$envFile}'.");
        }
    }

    /**
     * Load database dump
     *
     * @return void
     */
    private static function loadDump()
    {
        $mysqlParams = [
            'DB_HOST' => '--host=',
            'DB_USER' => '--user=',
            'DB_PASSWORD' => '--password=',
            'DB_NAME' => '',
        ];

        $dbOptions = '';
        foreach ($mysqlParams as $param => $key) {
            $dbOptions .= "{$key}{$_ENV[$param]} ";
        }

        $args = self::$args;

        $sshConfig = $args->getArgument('preset');

        $cmd = "restic -r sftp:bitrix@${sshConfig}:/backup/restic-repo -p /home/bitrix/scripts/restic.password dump --tag=mysql,{$_ENV['DB_NAME']} latest {$_ENV['DB_NAME']}.sql | mysql $dbOptions";

        if (!$args->isQuiet()) {
            echo($cmd);
        }

        if (!$args->isDryRun()) {
            exec($cmd);
        }
    }
}

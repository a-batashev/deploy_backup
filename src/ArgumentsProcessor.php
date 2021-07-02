<?php

namespace App;

/**
 * Process arguments of command line
 */
class ArgumentsProcessor
{
    /**
     * Processed CLI arguments
     *
     * @var array
     */
    protected static $arguments = [];

    /**
     * Processed CLI options
     *
     * @var array
     */
    protected static $options = [];

    /**
     * Processed CLI commands
     *
     * @var array
     */
    protected static $commands = [];

    /**
     * Instance of ArgumentsProcessor
     *
     * @var ArgumentsProcessor
     */
    protected static $instance;

    /**
     * Construct is hidden for singleton
     */
    private function __construct()
    {
    }

    /**
     * Disables cloning for singleton
     */
    private function __clone()
    {
    }

    /**
     * Disables unserialize for singleton
     */
    private function __wakeup()
    {
    }

    /**
     * Process CLI arguments
     *
     * @param array $args
     * @return ArgumentsProcessor
     */
    public function process(array $args)
    {
        $filteredArgs = array_filter($args);

        foreach ($filteredArgs as $arg => $value) {
            $firstChr = mb_substr($arg, 0, 1);

            switch ($firstChr) {
                case '<':
                    $key = trim($arg, '<>');
                    self::$arguments[$key] = $value;
                    break;
                case '-':
                    $key = ltrim($arg, '-');
                    self::$options[$key] = $value;
                    break;
                default:
                    if (!in_array($arg, self::$commands)) {
                        self::$commands[] = $arg;
                    }
                    break;
            }
        }

        return self::$instance;
    }

    /**
     * Return instance of ArgumentsProcessor
     *
     * @return ArgumentsProcessor
     */
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getArguments()
    {
        return self::$arguments;
    }

    public function getArgument(string $name)
    {
        return self::$arguments[$name] ?? null;
    }

    public function getOptions()
    {
        return self::$options;
    }

    public function getOption(string $name)
    {
        return self::$options[$name] ?? null;
    }

    public function getCommands()
    {
        return self::$commands;
    }

    public function getCommand(int $index = 0)
    {
        return self::$commands[$index] ?? null;
    }

    public function isDryRun(): bool
    {
        return self::$instance->getOption('dry-run') ?? false;
    }

    public function isQuiet(): bool
    {
        return self::$instance->getOption('quiet') ?? false;
    }
}

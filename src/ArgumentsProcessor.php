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
    private $arguments = [];

    /**
     * Processed CLI options
     *
     * @var array
     */
    private $options = [];

    /**
     * Processed CLI commands
     *
     * @var array
     */
    private $commands = [];

    /**
     * Construct
     *
     * @param array $args
     */
    public function __construct(array $args)
    {
        $filteredArgs = array_filter($args);

        foreach ($filteredArgs as $arg => $value) {
            $firstChr = mb_substr($arg, 0, 1);

            switch ($firstChr) {
                case '<':
                    $key = trim($arg, '<>');
                    $this->arguments[$key] = $value;
                    break;
                case '-':
                    $key = ltrim($arg, '-');
                    $this->options[$key] = $value;
                    break;
                default:
                    $this->commands[] = $arg;
                    break;
            }
        }
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function getArgument(string $name)
    {
        return $this->arguments[$name] ?? null;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getOption(string $name)
    {
        return $this->options[$name] ?? null;
    }

    public function getCommands()
    {
        return $this->commands;
    }

    public function getCommand(int $index = 0)
    {
        return $this->commands[$index] ?? null;
    }
}

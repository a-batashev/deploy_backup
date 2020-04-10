<?php

namespace App\Config;

/**
 * Parses configuration file
 */
class Parser
{
    /**
     * Path to the configuration file
     *
     * @var string
     */
    private $file;

    /**
     * Construct
     *
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * Create an instance
     *
     * @param string $file
     * @return object
     */
    public static function init(string $file)
    {
        return new self($file);
    }

    /**
     * Parses configuration file
     *
     * @return array
     */
    public function run(): array
    {
        $parser = $this->chooseParser();

        return $this->parseFile($parser);
    }

    /**
     * Parser dispatching
     *
     * @return void
     */
    private function chooseParser()
    {
        $extension = pathinfo($this->file, PATHINFO_EXTENSION);

        switch ($extension) {
            case 'yaml':
            case 'json':
                return new ParserYaml();
                break;
            default:
                throw new \Exception("Parser of '{$extension}' not found.");
                break;
        }
    }

    /**
     * Parse configuration file with a parser
     *
     * @param ParserInterface $parser
     * @return array
     */
    private function parseFile(ParserInterface $parser): array
    {
        return $parser::parseFile($this->file);
    }
}

#!/usr/bin/env php
<?php

use App\App;

require __DIR__ . '/../vendor/autoload.php';

$doc = <<<DOC
Deploy a backup

Usage:
    cli.php clean <preset> [-c<file> -d -q]
    cli.php download <preset> [-c<file> -d -q]
    cli.php database <preset> [-c<file> -d -q]
    cli.php configure <preset> [-c<file> -d -q]
    cli.php deploy <preset> [-c<file> -d -q]

Options:
    -c, --config=<file>  Configuration file (default: config.yaml)
    -d, --dry-run        Only print results
    -h, --help           Show this screen
    -q, --quiet          Don't use output
DOC;

$docopt = Docopt::handle($doc);

try {
    $app = new App($docopt->args);
    $app->run();
} catch (\Exception $e) {
    echo 'Error! ', $e->getMessage(), PHP_EOL;
    exit(1);
}

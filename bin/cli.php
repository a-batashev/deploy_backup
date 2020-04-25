#!/usr/bin/env php
<?php

use App\App;

require __DIR__ . '/../vendor/autoload.php';

$doc = <<<DOC
Deploy a backup

Usage:
    cli.php clean <preset> [-d -c<file>]
    cli.php download <preset> [-d -c<file>]
    cli.php database <preset> [-d -c<file>]
    cli.php configure <preset> [-d -c<file>]
    cli.php deploy <preset> [-d -c<file>]

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

#!/usr/bin/env php
<?php

use App\App;

require __DIR__ . '/../vendor/autoload.php';

$doc = <<<DOC
Deploy a backup

Usage:
    cli.php clean <preset> [-d]
    cli.php download <preset> [-d]
    cli.php configure <preset> [-d]
    cli.php deploy <preset> [-d]

Options:
    -d --dry-run    Only print results
    -q --quiet      Don't use output
DOC;

$docopt = Docopt::handle($doc);

try {
    $app = new App($docopt->args);
    $app->run();
} catch (\Exception $e) {
    echo 'Error! ', $e->getMessage(), PHP_EOL;
    exit(1);
}

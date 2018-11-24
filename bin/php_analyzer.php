#!/usr/bin/env php
<?php
use Mfn\PHP\Analyzer\Console;

require_once __DIR__ . '/../bootstrap.php';
ini_set('memory_limit', -1);

$app = new \Symfony\Component\Console\Application();
$app->add(new Console\Analyze());
$app->add(new Console\Graphviz());
$returnCode = $app->run();
exit($returnCode);

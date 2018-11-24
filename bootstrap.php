<?php
use Mfn\PHP\Analyzer\Util\Util;

# Try to locate composer autoloader
$foundAutoload = false;
foreach (['/../../autoload.php', '/vendor/autoload.php'] as $relDir) {
    if (file_exists(__DIR__ . $relDir)) {
        require __DIR__ . $relDir;
        $foundAutoload = true;
        break;
    }
}

if (!$foundAutoload) {
    echo 'Unable to find composer infrastructure, please see' . PHP_EOL;
    echo 'https://github.com/mfn/php-analyzer for installation.' . PHP_EOL;
    exit(1);
}

unset($relDir, $foundAutoload);
error_reporting(E_ALL);
Util::installMinimalError2ExceptionHandler();

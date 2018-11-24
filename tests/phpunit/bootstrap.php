<?php declare(strict_types=1);
use Mfn\PHP\Analyzer\Util\Util;

# Fall back to UTC if date.timezone is not set
$dateTimezone = ini_get('date.timezone');
if (empty($dateTimezone)) {
    date_default_timezone_set('UTC');
}

error_reporting(E_ALL);

Util::installMinimalError2ExceptionHandler();

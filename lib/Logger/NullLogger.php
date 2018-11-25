<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Logger;

/**
 * Don't log anything.
 *
 * Can be useful for e.g. unit testing where we don't care about the logs.
 */
final class NullLogger extends ProjectLogger
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    protected function realLog($level, $message, array $context = []): void
    {
    }
}

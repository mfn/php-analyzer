<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers;

/**
 * Describe the severity of all reports of a given analyzer.
 */
abstract class Severity
{
    const ERROR = 1;
    const WARNING = 2;

    /**
     * @param integer $level
     * @return string
     */
    public static function toString($level)
    {
        switch ($level) {
            case self::ERROR:
                return 'ERROR';
            case self::WARNING:
                return 'WARNING';
            default:
                throw new \RuntimeException("Unknown severity level $level");
        }
    }
}

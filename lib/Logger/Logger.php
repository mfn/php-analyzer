<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Logger;

use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * PSR-3 compatible logging infrastructure.
 *
 * Call any of the debug(), info() etc. methods or directly log().
 *
 * Provides helper for converting loglevels to string/int and context
 * interpolation.
 */
abstract class Logger implements LoggerInterface
{

    # RFC 5424
    const DEBUG = 100;
    const INFO = 200;
    const NOTICE = 250;
    const WARNING = 300;
    const ERROR = 400;
    const CRITICAL = 500;
    const ALERT = 550;
    const EMERGENCY = 600;
    /**
     * Only report this level "and above"
     *
     * "above" being defined as in "has a higher" value.
     * In that sense, ALERT is above INFO, INFO is above DEBUG, etc.
     *
     * @var int
     */
    protected $reportingLevel = self::WARNING;

    /**
     * Interpolate {} placeholders with information form context array
     *
     * A context array is a key=>value object; the value will be explicitly cast
     * to a string.
     *
     * Placeholders with no matching context value will not be touched upon.
     *
     * @param string $msg
     * @param array $context
     * @return string
     */
    public static function interpolateContext($msg, array $context): string
    {
        if (!preg_match_all('/{([A-Za-z0-9_.]+)}/', $msg, $matches)) {
            return $msg;
        }
        $transform = [];
        foreach ($matches[1] as $match) {
            if (isset($context[$match])) {
                $transform["{$match}"] = (string)$context[$match];
            }
        }
        return strtr($msg, $transform);
    }

    public function debug($message, array $context = [])
    {
        return $this->log(self::DEBUG, $message, $context);
    }

    /**
     * The central function receiving a log message
     *
     * The default implementation checks with the reportingLevel and only if the
     * level to log is equal or higher, the message is logged.
     *
     * @param int|string $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        $level = $this->ensureLevelIsvalidInt($level);
        if ($level >= $this->reportingLevel) {
            $this->realLog($level, $message, $context);
        }
        return null;
    }

    /**
     * @param mixed $level
     * @return int
     */
    protected function ensureLevelIsvalidInt($level): int
    {
        if (!is_int($level)) {
            try {
                $level = self::levelToInt((string)$level);
            } catch (\Exception $e) {
                throw new InvalidArgumentException('Unknown loglevel', 0, $e);
            }
        } else {
            self::levelToString($level); # Only validate
        }
        return $level;
    }

    /**
     * @param string $level
     * @return int
     */
    public static function levelToInt($level): int
    {
        switch ($level) {
            case LogLevel::DEBUG:
                return self::DEBUG;
            case LogLevel::NOTICE:
                return self::NOTICE;
            case LogLevel::INFO:
                return self::INFO;
            case LogLevel::WARNING:
                return self::WARNING;
            case LogLevel::ERROR:
                return self::ERROR;
            case LogLevel::CRITICAL:
                return self::CRITICAL;
            case LogLevel::ALERT:
                return self::ALERT;
            case LogLevel::EMERGENCY:
                return self::EMERGENCY;
        }
        throw new InvalidArgumentException('Unknown loglevel ' . $level);
    }

    /**
     * @param integer $level
     * @return string
     */
    public static function levelToString($level): string
    {
        switch ($level) {
            case self::DEBUG:
                return LogLevel::DEBUG;
            case self::NOTICE:
                return LogLevel::NOTICE;
            case self::INFO:
                return LogLevel::INFO;
            case self::WARNING:
                return LogLevel::WARNING;
            case self::ERROR:
                return LogLevel::ERROR;
            case self::CRITICAL:
                return LogLevel::CRITICAL;
            case self::ALERT:
                return LogLevel::ALERT;
            case self::EMERGENCY:
                return LogLevel::EMERGENCY;
        }
        throw new InvalidArgumentException('Unknown loglevel ' . $level);
    }

    /**
     * The actual implementation to perform the logging action
     *
     * The level is always int; if you want a string representation use
     * levelToInt().
     *
     * If you want to interpolate the context, use interpolateContext()
     *
     * @param int $level
     * @param string $message
     * @param array $context
     */
    abstract protected function realLog($level, $message, array $context = []): void;

    public function info($message, array $context = [])
    {
        return $this->log(self::INFO, $message, $context);
    }

    public function notice($message, array $context = [])
    {
        return $this->log(self::NOTICE, $message, $context);
    }

    public function warning($message, array $context = [])
    {
        return $this->log(self::WARNING, $message, $context);
    }

    public function error($message, array $context = [])
    {
        return $this->log(self::ERROR, $message, $context);
    }

    public function critical($message, array $context = [])
    {
        return $this->log(self::CRITICAL, $message, $context);
    }

    public function alert($message, array $context = [])
    {
        return $this->log(self::ALERT, $message, $context);
    }

    public function emergency($message, array $context = [])
    {
        return $this->log(self::EMERGENCY, $message, $context);
    }

    /**
     * @return int
     */
    public function getReportingLevel(): int
    {
        return $this->reportingLevel;
    }

    /**
     * @param int $reportingLevel
     * @return $this
     */
    public function setReportingLevel($reportingLevel): self
    {
        self::levelToString($reportingLevel); # this only serves as a check
        $this->reportingLevel = $reportingLevel;
        return $this;
    }
}

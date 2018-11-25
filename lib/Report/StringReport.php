<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Report;

/**
 * String in, string out; it does not get any easier.
 */
class StringReport extends Report
{
    protected $message = '';

    /**
     * @param string $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    public function report(): string
    {
        return $this->message;
    }
}

<?php
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

    /**
     * @return string
     */
    public function report()
    {
        return $this->message;
    }
}

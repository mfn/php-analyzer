<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Report;

/**
 * Represent lines in a source file, from -> to and optionally a line to
 * highlight.
 *
 * Line numbering starts at 0.
 */
class Lines
{

    /** @var int */
    private $from;
    /** @var int */
    private $to;
    /**
     * Optional line to highlight
     * @var NULL|int
     */
    private $highlightLine;

    /**
     * Line numbering starts at 0.
     * @param int $from
     * @param int $to
     * @param int|NULL $highlightLine
     */
    public function __construct($from, $to, $highlightLine = null)
    {
        if ($to < $from) {
            throw new \InvalidArgumentException('$to must be >= $from');
        }
        if (
            $highlightLine !== null
            &&
            !($from <= $highlightLine && $highlightLine <= $to)
        ) {
            throw new \InvalidArgumentException(
                'highlightLine must be between from and to, inclusive: '
                . "$from <= $highlightLine <= $to"
            );
        }
        $this->from = $from;
        $this->to = $to;
        $this->highlightLine = $highlightLine;
    }

    /**
     * @return int|NULL
     */
    public function getHighlightLine()
    {
        return $this->highlightLine;
    }

    /**
     * @return int
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return int
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return int
     */
    public function length()
    {
        return $this->to - $this->from;
    }

    /**
     * Return an array with serialize discrete values
     */
    public function toArray()
    {
        $data = [
            'from' => $this->from,
            'to' => $this->to,
            'highlight' => $this->highlightLine,
        ];
        return $data;
    }
}

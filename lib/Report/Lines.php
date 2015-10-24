<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Markus Fischer <markus@fischer.name>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
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

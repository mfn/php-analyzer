<?php
namespace Mfn\PHP\Analyzer\Report;

use Mfn\PHP\Analyzer\File;

class SourceFragment
{

    /** @var File */
    private $file;
    /** @var Lines */
    private $lineSegment;

    /**
     * @param File $file
     * @param Lines $lineSegment
     */
    public function __construct(File $file, Lines $lineSegment)
    {
        $this->file = $file;
        $this->lineSegment = $lineSegment;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Return an array with serialize discrete values
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'file' => $this->file->getSplFile()->getRealPath(),
            'lines' => $this->getLines(),
            'lineSegment' => $this->getLineSegment()->toArray()
        ];
        return $data;
    }

    /**
     * Returns the lines as array; the indices of the array match the line numbers
     *
     * Note: line numbers may be outside the source file which is handled
     *
     * @return string[]
     */
    public function getLines()
    {
        $lines = [];
        $source = $this->file->getSource();
        for (
            $line = $this->lineSegment->getFrom();
            $line <= $this->lineSegment->getTo();
            $line++
        ) {
            if (!isset($source[$line])) {
                continue;
            }
            $lines[$line] = $source[$line];
        }
        return $lines;
    }

    /**
     * @return Lines
     */
    public function getLineSegment()
    {
        return $this->lineSegment;
    }
}

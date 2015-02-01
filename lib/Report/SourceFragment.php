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

use Mfn\PHP\Analyzer\File;

class SourceFragment {

  /** @var File */
  private $file;
  /** @var Lines */
  private $lineSegment;

  /**
   * @param File $file
   * @param Lines $lineSegment
   */
  public function __construct(File $file, Lines $lineSegment) {
    $this->file = $file;
    $this->lineSegment = $lineSegment;
  }

  /**
   * @return File
   */
  public function getFile() {
    return $this->file;
  }

  /**
   * Return an array with serialize discrete values
   *
   * @return array
   */
  public function toArray() {
    $data = [
      'file'        => $this->file->getSplFile()->getRealPath(),
      'lines'       => $this->getLines(),
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
  public function getLines() {
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
  public function getLineSegment() {
    return $this->lineSegment;
  }
}

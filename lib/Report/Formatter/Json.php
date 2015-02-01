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
namespace Mfn\PHP\Analyzer\Report\Formatter;

use Mfn\PHP\Analyzer\Report\Report;

class Json implements Formatter {

  /**
   * Options to pass to json_encode()
   * @var int
   */
  private $options = 0;

  /**
   * @param int $options Options passed to json_encode()
   */
  public function __construct($options = 0) {
    $this->options = $options;
  }

  /**
   * @param Report $report
   * @return string
   */
  public function formatReport(Report $report) {
    return json_encode($report->toArray(), $this->options);
  }

  /**
   * @param Report[] $reports
   * @return string
   */
  public function formatReports(array $reports) {
    return json_encode(
      array_map(
        function (Report $report) {
          return $report->toArray();
        },
        $reports
      ),
      $this->options
    );
  }

  /**
   * @param int $options json_encode options
   * @return $this
   */
  public function setOptions($options) {
    $this->options = $options;
    return $this;
  }
}

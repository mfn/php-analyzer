<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Markus Fischer <markus@fischer.name>
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

use Mfn\PHP\Analyzer\Analyzers\Severity;
use Mfn\PHP\Analyzer\Report\AnalyzerReport;
use Mfn\PHP\Analyzer\Report\Report;
use Mfn\PHP\Analyzer\Report\SourceFragment;
use Mfn\PHP\Analyzer\Report\TimestampedReport;

class Plain implements Formatter {

  /**
   * @param Report $report
   * @return string
   */
  public function formatReport(Report $report) {
    return self::formatReportReadable($report);
  }

  /**
   * @param Report $report
   * @return string
   */
  public static function formatReportReadable(Report $report) {
    $message = '';
    if ($report instanceof TimestampedReport) {
      $message .= '[' . $report->getTimestamp()->format(\DateTime::ATOM) . ']';
    }
    if ($report instanceof AnalyzerReport) {
      $message .= '[' . Severity::toString($report->getAnalyzer()->getSeverity()) . ']';
      $message .= '[' . $report->getAnalyzer()->getName() . ']';
    }
    if (!empty($message)) {
      $message .= ' ';
    }
    $message .= $report->report() . PHP_EOL;
    if (NULL !== $sourceFragment = $report->getSourceFragment()) {
      $message .= self::formatSourceFragmentReadable($sourceFragment);
    }
    return $message;
  }

  /**
   * @param SourceFragment $fragment
   * @return string
   */
  public static function formatSourceFragmentReadable(SourceFragment $fragment) {
    $source = '  Source context for '
      . $fragment->getFile()->getSplFile()->getFilename()
      . ':' . PHP_EOL;
    $lines = $fragment->getLines();
    $lineNumbers = array_keys($lines);
    $lineNrLength = strlen((string) end($lineNumbers));
    $defaultSeparate = ' :';
    $highlightLine = $fragment->getLineSegment()->getHighlightLine();
    if (NULL !== $highlightLine) {
      $defaultSeparate = '  :';
    }
    foreach ($lines as $lineNr => $line) {
      $separate = $defaultSeparate;
      if ($highlightLine === $lineNr) {
        $separate = ' ->';
      }
      $source .= sprintf("  %" . $lineNrLength . "s%s %s" . PHP_EOL,
        $lineNr + 1,
        $separate,
        rtrim($line) # normalize EOL
      );
    }
    return $source;
  }

  /**
   * @param Report[] $reports
   * @return string
   */
  public function formatReports(array $reports) {
    return join('',
      array_map(
        function (Report $report) {
          return self::formatReportReadable($report) . PHP_EOL;
        },
        $reports
      )
    );
  }
}

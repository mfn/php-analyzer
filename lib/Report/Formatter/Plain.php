<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Report\Formatter;

use Mfn\PHP\Analyzer\Analyzers\Severity;
use Mfn\PHP\Analyzer\Report\AnalyzerReport;
use Mfn\PHP\Analyzer\Report\Report;
use Mfn\PHP\Analyzer\Report\SourceFragment;
use Mfn\PHP\Analyzer\Report\TimestampedReport;

class Plain implements Formatter
{

    public function formatReport(Report $report): string
    {
        return self::formatReportReadable($report);
    }

    /**
     * @param Report $report
     * @return string
     */
    public static function formatReportReadable(Report $report): string
    {
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
        if (null !== $sourceFragment = $report->getSourceFragment()) {
            $message .= self::formatSourceFragmentReadable($sourceFragment);
        }
        return $message;
    }

    /**
     * @param SourceFragment $fragment
     * @return string
     */
    public static function formatSourceFragmentReadable(SourceFragment $fragment): string
    {
        $source = '  Source context for '
            . $fragment->getFile()->getSplFile()->getFilename()
            . ':' . PHP_EOL;
        $lines = $fragment->getLines();
        $lineNumbers = array_keys($lines);
        $lineNrLength = strlen((string)end($lineNumbers));
        $defaultSeparate = ' :';
        $highlightLine = $fragment->getLineSegment()->getHighlightLine();
        if (null !== $highlightLine) {
            $defaultSeparate = '  :';
        }
        foreach ($lines as $lineNr => $line) {
            $separate = $defaultSeparate;
            if ($highlightLine === $lineNr) {
                $separate = ' ->';
            }
            $source .= sprintf(
                "  %" . $lineNrLength . "s%s %s" . PHP_EOL,
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
    public function formatReports(array $reports): string
    {
        return join(
            '',
            array_map(
                function (Report $report) {
                    return self::formatReportReadable($report) . PHP_EOL;
                },
                $reports
            )
        );
    }
}

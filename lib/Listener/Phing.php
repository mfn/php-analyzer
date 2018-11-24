<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Listener;

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Analyzers\Severity;
use Mfn\PHP\Analyzer\PhingTask;
use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Report\AnalyzerReport;
use Mfn\PHP\Analyzer\Report\Formatter\Json;
use Mfn\PHP\Analyzer\Report\Formatter\Plain;

class Phing implements Listener
{

    /** @var PhingTask */
    private $task;
    /** @var string */
    private $buildErrorMessage = '';
    /** @var \FileWriter|NULL */
    private $logfileWriter = null;
    /** @var string */
    private $logFormat = '';

    public function __construct(PhingTask $task)
    {
        $this->task = $task;
        $this->logfileWriter = $task->getLogfile()
            ? new \FileWriter($task->getLogfile())
            : null;
        $this->logFormat = $task->getLogFormat();
    }

    public function projectStart(Project $project)
    {
    }

    public function projectEnd(Project $project)
    {
        if ($this->logfileWriter && 0 === strpos($this->logFormat, 'json')) {
            $options = $this->logFormat === 'json-pretty'
                ? JSON_PRETTY_PRINT
                : 0;
            $formatter = new Json($options);
            /** @noinspection PhpParamsInspection */
            $this->logfileWriter->write(
                $formatter->formatReports(
                    $project->getAnalyzerReports()
                )
            );
        }
    }

    public function beforeAnalyzer(Analyzer $analyzer)
    {
        $this->task->log(
            'Running analyzer ' . $analyzer->getName(),
            \Project::MSG_VERBOSE
        );
    }

    public function afterAnalyzer(Analyzer $analyzer)
    {
    }

    public function addReport(AnalyzerReport $analyzerReport)
    {
        $report = $analyzerReport->getTimestampedReport()->getReport();

        # Check if an analyzer error should translate to a build error
        if (
            $this->task->isHaltonerror() &&
            '' === $this->buildErrorMessage &&
            $analyzerReport->getAnalyzer()->getSeverity() === Severity::ERROR
        ) {
            $this->buildErrorMessage = $report->report();
        }

        # Check if an analyzer warning should translate to a build error
        if (
            $this->task->isHaltonwarning() &&
            '' === $this->buildErrorMessage &&
            $analyzerReport->getAnalyzer()->getSeverity() === Severity::WARNING
        ) {
            $this->buildErrorMessage = $report->report();
        }

        if ($this->logfileWriter && $this->logFormat === 'plain') {
            /** @noinspection PhpParamsInspection */
            $this->logfileWriter->write(Plain::formatReportReadable($analyzerReport));
        } else {
            $this->task->log(
                self::formatReport($analyzerReport),
                self::severityToPhingLevel(
                    $analyzerReport->getAnalyzer()->getSeverity()
                )
            );
        }
    }

    /**
     * @param AnalyzerReport $analyzerReport
     * @return string
     */
    private static function formatReport(AnalyzerReport $analyzerReport)
    {
        $timestampedReport = $analyzerReport->getTimestampedReport();
        $report = $timestampedReport->getReport();
        $message = sprintf(
            '[%s] %s' . PHP_EOL,
            $analyzerReport->getAnalyzer()->getName(),
            $report->report()
        );
        if (null !== $sourceFragment = $report->getSourceFragment()) {
            $fragment = Plain::formatSourceFragmentReadable($sourceFragment);
            $message .= $fragment;
        }
        return $message;
    }

    /**
     * @param int $severity
     * @return int
     */
    private static function severityToPhingLevel($severity)
    {
        switch ($severity) {
            case Severity::ERROR:
                return \Project::MSG_ERR;
            case Severity::WARNING:
                return \Project::MSG_WARN;
            default:
                throw new \RuntimeException('Unknown severity ' . $severity);
        }
    }

    /**
     * @return string
     */
    public function getBuildErrorMessage()
    {
        return $this->buildErrorMessage;
    }
}

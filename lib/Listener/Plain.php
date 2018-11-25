<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Listener;

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Report\AnalyzerReport;
use Mfn\PHP\Analyzer\Report\Formatter\Plain as PlainFormatter;

/**
 * A plain text formatter
 */
class Plain extends FilePointerWriter
{
    public function addReport(AnalyzerReport $analyzerReport): void
    {
        $this->write(PlainFormatter::formatReportReadable($analyzerReport));
    }

    public function projectStart(Project $project): void
    {
        // TODO: Implement projectStart() method.
    }

    public function projectEnd(Project $project): void
    {
        // TODO: Implement projectEnd() method.
    }

    public function beforeAnalyzer(Analyzer $analyzer): void
    {
        // TODO: Implement beforeAnalyzer() method.
    }

    public function afterAnalyzer(Analyzer $analyzer): void
    {
        // TODO: Implement afterAnalyzer() method.
    }
}

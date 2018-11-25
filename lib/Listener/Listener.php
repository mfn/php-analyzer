<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Listener;

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Report\AnalyzerReport;

interface Listener
{
    public function projectStart(Project $project): void;

    public function projectEnd(Project $project): void;

    public function beforeAnalyzer(Analyzer $analyzer): void;

    public function afterAnalyzer(Analyzer $analyzer): void;

    public function addReport(AnalyzerReport $analyzerReport): void;
}

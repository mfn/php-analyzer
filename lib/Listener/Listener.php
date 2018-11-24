<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Listener;

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Report\AnalyzerReport;

interface Listener
{
    public function projectStart(Project $project);

    public function projectEnd(Project $project);

    public function beforeAnalyzer(Analyzer $analyzer);

    public function afterAnalyzer(Analyzer $analyzer);

    public function addReport(AnalyzerReport $analyzerReport);
}

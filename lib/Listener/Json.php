<?php
namespace Mfn\PHP\Analyzer\Listener;

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Report\AnalyzerReport;
use Mfn\PHP\Analyzer\Report\Formatter\Json as JsonFormatter;

class Json extends FilePointerWriter
{

    /** @var JsonFormatter */
    private $json;

    public function __construct($fd, JsonFormatter $json)
    {
        parent::__construct($fd);
        $this->json = $json;
    }

    public function projectStart(Project $project)
    {
        // TODO: Implement projectStart() method.
    }

    public function projectEnd(Project $project)
    {
        $this->write($this->json->formatReports($project->getAnalyzerReports()));
    }

    public function beforeAnalyzer(Analyzer $analyzer)
    {
        // TODO: Implement beforeAnalyzer() method.
    }

    public function afterAnalyzer(Analyzer $analyzer)
    {
        // TODO: Implement afterAnalyzer() method.
    }

    public function addReport(AnalyzerReport $analyzerReport)
    {
        // TODO: Implement addReport() method.
    }
}

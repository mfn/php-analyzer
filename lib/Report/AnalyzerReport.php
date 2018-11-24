<?php
namespace Mfn\PHP\Analyzer\Report;

use Mfn\PHP\Analyzer\Analyzers\Analyzer;

class AnalyzerReport extends Report
{

    /** @var Analyzer */
    private $analyzer;
    /** @var TimestampedReport */
    private $report;

    public function __construct(Analyzer $analyzer, TimestampedReport $report)
    {
        $this->analyzer = $analyzer;
        $this->report = $report;
    }

    /**
     * @return Analyzer
     */
    public function getAnalyzer()
    {
        return $this->analyzer;
    }

    /**
     * @return TimestampedReport
     */
    public function getTimestampedReport()
    {
        return $this->report;
    }

    /**
     * @return string
     */
    public function report()
    {
        return $this->report->report();
    }

    public function getSourceFragment()
    {
        return $this->report->getSourceFragment();
    }

    public function setSourceFragment(SourceFragment $sourceFragment)
    {
        return $this->report->setSourceFragment($sourceFragment);
    }

    /**
     * Return an array with serialize discrete values
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'analyzer' => $this->analyzer->getName(),
        ] + $this->report->toArray();
    }
}

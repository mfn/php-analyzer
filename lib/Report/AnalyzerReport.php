<?php declare(strict_types=1);
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
    public function getAnalyzer(): Analyzer
    {
        return $this->analyzer;
    }

    /**
     * @return TimestampedReport
     */
    public function getTimestampedReport(): TimestampedReport
    {
        return $this->report;
    }

    public function report(): string
    {
        return $this->report->report();
    }

    public function getSourceFragment(): ?SourceFragment
    {
        return $this->report->getSourceFragment();
    }

    public function setSourceFragment(SourceFragment $sourceFragment): Report
    {
        return $this->report->setSourceFragment($sourceFragment);
    }

    /**
     * Return an array with serialize discrete values
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'analyzer' => $this->analyzer->getName(),
        ] + $this->report->toArray();
    }
}

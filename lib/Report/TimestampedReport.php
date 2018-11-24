<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Report;

class TimestampedReport extends Report
{

    /** @var Report */
    private $report;
    /** @var \DateTime */
    private $timestamp;

    public function __construct(Report $report)
    {
        $this->timestamp = new \DateTime();
        $this->report = $report;
    }

    /**
     * @return Report
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
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

    public function toArray()
    {
        return [
            'timestamp' => $this->timestamp->format(\DateTime::ATOM),
        ] + $this->report->toArray();
    }
}

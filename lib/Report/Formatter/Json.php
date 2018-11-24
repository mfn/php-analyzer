<?php
namespace Mfn\PHP\Analyzer\Report\Formatter;

use Mfn\PHP\Analyzer\Report\Report;

class Json implements Formatter
{

    /**
     * Options to pass to json_encode()
     * @var int
     */
    private $options = 0;

    /**
     * @param int $options Options passed to json_encode()
     */
    public function __construct($options = 0)
    {
        $this->options = $options;
    }

    /**
     * @param Report $report
     * @return string
     */
    public function formatReport(Report $report)
    {
        return json_encode($report->toArray(), $this->options);
    }

    /**
     * @param Report[] $reports
     * @return string
     */
    public function formatReports(array $reports)
    {
        return json_encode(
            array_map(
                function (Report $report) {
                    return $report->toArray();
                },
                $reports
            ),
            $this->options
        );
    }

    /**
     * @param int $options json_encode options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
}

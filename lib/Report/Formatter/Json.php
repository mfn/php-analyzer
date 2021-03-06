<?php declare(strict_types=1);
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

    public function formatReport(Report $report): string
    {
        return json_encode($report->toArray(), $this->options);
    }

    /**
     * @param Report[] $reports
     * @return string
     */
    public function formatReports(array $reports): string
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
    public function setOptions($options): self
    {
        $this->options = $options;
        return $this;
    }
}

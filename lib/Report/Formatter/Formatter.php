<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Report\Formatter;

use Mfn\PHP\Analyzer\Report\Report;

interface Formatter
{

    /**
     * @param Report $report
     * @return string
     */
    public function formatReport(Report $report);

    /**
     * @param Report[] $reports
     * @return string
     */
    public function formatReports(array $reports);
}

<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Report\Formatter;

use Mfn\PHP\Analyzer\Report\Report;

interface Formatter
{

    public function formatReport(Report $report): string;

    /**
     * @param Report[] $reports
     * @return string
     */
    public function formatReports(array $reports): string;
}

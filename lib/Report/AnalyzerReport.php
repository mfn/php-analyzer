<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Markus Fischer <markus@fischer.name>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
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

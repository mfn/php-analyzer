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
namespace Mfn\PHP\Analyzer\Analyzers;

use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\ObjectGraph;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedInterface;
use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Report\Lines;
use Mfn\PHP\Analyzer\Report\SourceFragment;
use Mfn\PHP\Analyzer\Report\StringReport;

/**
 * Finds wrong interface method access types.
 *
 * Currently it reports `abstract function` when appearing as part of an
 * interface. This analyzer isn't really that useful because this error
 * also covered by the PHP linter itself.
 */
class InterfaceMethodAbstract extends Analyzer
{

    /** @var ObjectGraph */
    private $graph;

    public function __construct(ObjectGraph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'InterfaceMethodAbstract';
    }

    /**
     * @param Project $project
     */
    public function analyze(Project $project)
    {
        foreach ($this->graph->getObjects() as $object) {
            if ($object instanceof ParsedInterface) {
                foreach ($object->getMethods() as $method) {
                    if ($method->getMethod()->isAbstract()) {
                        $report = new StringReport(
                            'Access type for interface method '
                            . $object->getName() . '::'
                            . $method->getNameAndParamsSignature()
                            . ' must be ommmited in '
                            . $object->getFile()->getSplFile()->getRealPath() . ':'
                            . $object->getInterface()->getLine()
                        );
                        $report->setSourceFragment(
                            new SourceFragment(
                                $object->getFile(),
                                new Lines(
                                    $method->getMethod()->getAttribute('startLine') - 1 - $this->sourceContext,
                                    $method->getMethod()->getAttribute('endLine') - 1 + $this->sourceContext,
                                    $method->getMethod()->getAttribute('startLine') - 1
                                )
                            )
                        );
                        $project->addReport($report);
                    }
                }
            }
        }
    }
}

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
namespace Mfn\PHP\Analyzer\Analyzers\MethodCompatibility;

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\Helper;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\ObjectGraph;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedInterface;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedMethod;
use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Report\Lines;
use Mfn\PHP\Analyzer\Report\SourceFragment;

/**
 * Find all override methods whose signature has changed.
 *
 * Currently the PHP internal cannot detect this on static analysis, only
 * during runtime are these errors exposed.
 */
class MethodCompatibility extends Analyzer
{

    /** @var ObjectGraph */
    private $graph;

    public function __construct(ObjectGraph $graph)
    {
        $this->graph = $graph;
        $this->helper = new Helper($graph);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'MethodCompatibility';
    }

    /**
     * @param Project $project
     */
    public function analyze(Project $project)
    {
        foreach ($this->graph->getObjects() as $object) {
            if ($object instanceof ParsedInterface) {
                foreach ($object->getMethods() as $method) {
                    $methodCompare = new MethodSignatureCompare($method);
                    foreach (
                        $this->checkInterfaceMethods(
                            $methodCompare,
                            $this->helper->findInterfaceImplements($object)
                        ) as $brokenInterfaceAndMethod) {
                        $report = new Report(
                            $brokenInterfaceAndMethod,
                            $method
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

    /**
     * Recursively compares the provided method against every other method in the
     * provided interfaces and checks their implementors too.
     *
     * @param MethodSignatureCompare $methodCompareTo
     * @param ParsedInterface[] $interfaces
     * @return ParsedMethod[]
     */
    private function checkInterfaceMethods(
        MethodSignatureCompare $methodCompareTo,
        array $interfaces
    ) {
        $compareToName = $methodCompareTo->getMethod()->getNormalizedName();
        $mismatchedMethods = [];
        foreach ($interfaces as $interface) {
            foreach ($interface->getMethods() as $method) {
                if ($method->getNormalizedName() === $compareToName) {
                    if (
                        $methodCompareTo->getSignature()
                        !==
                        MethodSignatureCompare::generateMethodSignature($method->getMethod())
                    ) {
                        $mismatchedMethods[] = $method;
                    }
                }
            }
            $mismatchedMethods = array_merge(
                $mismatchedMethods,
                $this->checkInterfaceMethods(
                    $methodCompareTo,
                    $this->helper->findInterfaceImplements($interface)
                )
            );
        }
        return $mismatchedMethods;
    }
}

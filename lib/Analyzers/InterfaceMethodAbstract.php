<?php declare(strict_types=1);
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

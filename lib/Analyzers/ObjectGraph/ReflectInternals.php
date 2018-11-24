<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\ObjectGraph;

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Analyzers\Tools\ReflectedObject;
use Mfn\PHP\Analyzer\Project;

/**
 * Adds PHPs internal classes to the ObjectGraph so those and their methods
 * are resolvable too.
 *
 * Note: the actual classes/interfaces added depend on which are available
 * PHPs runtime when running this analyzer.
 */
class ReflectInternals extends Analyzer
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
        return 'ObjectGraphReflectInternals';
    }

    /**
     * @param Project $project
     */
    public function analyze(Project $project)
    {
        # Necessary, otherwise the graph doesn't know of the project because only
        # within it's own analyzer lifecycle would it receive it
        $this->graph->setProject($project);
        $numClasses = 0;
        $numInterfaces = 0;
        foreach ([get_declared_classes(), get_declared_interfaces()] as $set) {
            foreach ($set as $name) {
                $reflector = new \ReflectionClass($name);
                if ($reflector->isUserDefined()) {
                    continue;
                }
                $object = ReflectedObject::createFromReflectionClass($reflector);
                $this->graph->addObject($object);
                if ($reflector->isInterface()) {
                    $numInterfaces++;
                } else {
                    if (!$reflector->isTrait()) {
                        $numClasses++;
                    }
                }
            }
        }
        $project->getLogger()->info(
            sprintf('Found %d classes and %d interfaces', $numClasses, $numInterfaces)
        );
        $this->graph->resolveGraph();
    }
}

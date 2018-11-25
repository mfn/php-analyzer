<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\MissingMethod;

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\Helper;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\ObjectGraph;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedClass;
use Mfn\PHP\Analyzer\Project;
use Mfn\Util\Map\SimpleOrderedValidatingMap as Map;
use Mfn\Util\Map\SimpleOrderedValidatingMapException as MapException;

/**
 * Find un-implemented abstract methods
 *
 * If you define an abstract method and forget to implement it, the PHP linter
 * can only warn you if both are in the same file.
 *
 * This analyzer, based on the *ObjectGraph Analyzer*, finds all sub-classes
 * missing abstract method implementations across your project.
 */
class AbstractMissing extends Analyzer
{

    /** @var ObjectGraph */
    private $objectGraph;

    public function __construct(ObjectGraph $objectGraph)
    {
        $this->objectGraph = $objectGraph;
        $this->helper = new Helper($objectGraph);
    }

    public function analyze(Project $project): void
    {
        $graph = $this->objectGraph;
        /** @var ParsedClass[] $classesMissingMethod */
        $classesMissingMethods = new Map(
            function ($key) {
                if ($key instanceof ParsedClass) {
                    return;
                }
                throw new MapException('Only keys of type Class_ are accepted');
            },
            function ($value) {
                if (is_array($value)) {
                    return;
                }
                throw new MapException('Only values of type array are accepted');
            }
        );
        # scan all objects (we're actually only interested in classes)
        foreach ($graph->getObjects() as $object) {
            if ($object instanceof ParsedClass) {
                foreach ($object->getMethods() as $method) {
                    # and see if they've abstract methods
                    if ($method->getMethod()->isAbstract()) {
                        $methodName = $method->getNormalizedName();
                        # now find all descendant classes and see if they've implemented it
                        $classesMissingMethod =
                            $this->findSubtypeUntilMethodMatchesRecursive(
                                $methodName,
                                $this->helper->findExtends($object)
                            );
                        # in case we found ones, store them for reporting later
                        # note: we may find other methods in the same class later too
                        foreach ($classesMissingMethod as $classMissingMethod) {
                            $methods = [];
                            if ($classesMissingMethods->exists($classMissingMethod)) {
                                $methods = $classesMissingMethods->get($classMissingMethod);
                            }
                            $methods[] = $method;
                            $classesMissingMethods->set($classMissingMethod, $methods);
                        }
                    }
                }
            }
        }
        /** @var ParsedClass $class */
        foreach ($classesMissingMethods->keys() as $class) {
            $project->addReport(
                new AbstractMissingReport(
                    $class,
                    $classesMissingMethods->get($class)
                )
            );
        }
    }

    /**
     * Recursively searches "down" the object graph to find classes missing said
     * method.
     *
     * Only descendants of abstract classes missing the methods are further
     * searched; normal classes are reported immediately.
     *
     * @param string $methodName
     * @param ParsedClass[] $classes
     * @return ParsedClass[]
     */
    private function findSubtypeUntilMethodMatchesRecursive($methodName, $classes): array
    {
        $classesMissingMethod = [];
        foreach ($classes as $class) {
            $foundMethod = false;
            foreach ($class->getMethods() as $method) {
                if ($methodName === $method->getNormalizedName()) {
                    $foundMethod = true;
                    break;
                }
            }

            # abstract classes must not implement it ...
            if ($class->getClass()->isAbstract()) {
                if (!$foundMethod) {
                    # ... but we search further down the inheritance tree
                    $classesMissingMethod = array_merge(
                        $classesMissingMethod,
                        $this->findSubtypeUntilMethodMatchesRecursive(
                            $methodName,
                            $this->helper->findExtends($class)
                        )
                    );
                }
                continue;
            }

            if (!$foundMethod) {
                $classesMissingMethod[] = $class;
            }
        }
        return $classesMissingMethod;
    }

    public function getName(): string
    {
        return 'AbstractMissing';
    }
}

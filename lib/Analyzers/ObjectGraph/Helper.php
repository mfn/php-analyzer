<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\ObjectGraph;

use Mfn\PHP\Analyzer\Analyzers\Tools\Class_;
use Mfn\PHP\Analyzer\Analyzers\Tools\Interface_;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedClass;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedInterface;

/**
 * Provide useful helper methods on a parsed object graph
 */
class Helper
{

    /** @var  ObjectGraph */
    private $graph;

    public function __construct(ObjectGraph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * Checks if "$class instanceof $interface"
     *
     * @param NULL|Class_ $class
     * @param Interface_ $superInterface
     * @return bool
     */
    public static function classImplements(
        Class_ $class = null,
        Interface_ $superInterface
    ): bool {
        if (null === $class) {
            return false;
        }
        foreach ($class->getInterfaces() as $interface) {
            if ($interface === $superInterface) {
                return true;
            }
        }
        return self::classImplements($class->getParent(), $superInterface);
    }

    /**
     * Finds all classes extending the provided one
     *
     * @param ParsedClass $class
     * @param bool $recursive Whether to find all descendants; off by default
     * @return ParsedClass[]
     */
    public function findExtends(ParsedClass $class, $recursive = false): array
    {
        $found = [];
        foreach ($this->graph->getClasses() as $object) {
            if ($class === $object->getParent()) {
                $found[] = $object;
                if ($recursive) {
                    $found = array_merge(
                        $found,
                        $this->findExtends($object, $recursive)
                    );
                }
            }
        }
        return $found;
    }

    /**
     * Finds all classes or interfaces implementing the provided one
     *
     * @param ParsedInterface $interface
     * @return ParsedInterface[]|ParsedClass[]
     */
    public function findImplements(ParsedInterface $interface): array
    {
        $found = [];
        foreach ($this->graph->getObjects() as $object) {
            if ($object instanceof ParsedClass) {
                foreach ($object->getInterfaces() as $implements) {
                    if ($interface === $implements) {
                        $found[] = $object;
                        break;
                    }
                }
            } else {
                if ($object instanceof ParsedInterface) {
                    foreach ($object->getInterfaces() as $extends) {
                        if ($interface === $extends) {
                            $found[] = $object;
                            break;
                        }
                    }
                }
            }
        }
        return $found;
    }

    /**
     * Finds all interfaces implementing the provided one
     *
     * @param ParsedInterface $interface
     * @return ParsedInterface[]
     */
    public function findInterfaceImplements(ParsedInterface $interface): array
    {
        $found = [];
        foreach ($this->graph->getInterfaces() as $object) {
            foreach ($object->getInterfaces() as $extends) {
                if ($interface === $extends) {
                    $found[] = $object;
                    break;
                }
            }
        }
        return $found;
    }
}

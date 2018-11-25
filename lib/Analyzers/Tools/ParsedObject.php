<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

use Mfn\PHP\Analyzer\File;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;

/**
 * Represent the base for a  class or interface.
 */
abstract class ParsedObject implements GenericObject, Parsed
{

    /**
     * Full qualified name
     * @var string
     */
    protected $fqn;
    /** @var string */
    protected $namespace = '';
    /** @var File */
    protected $file;
    /** @var Use_[] */
    protected $uses = [];
    /** @var Node */
    protected $node;

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }

    /**
     * @return Node
     */
    public function getNode(): Node
    {
        return $this->node;
    }

    public function getName(): string
    {
        return $this->fqn;
    }

    /**
     * Return an string identifier for this kind of object; no harm using
     * something human readable like 'Class', 'Interface', etc.
     *
     * @return string
     */
    abstract public function getKind(): string;

    /**
     * Get namespace name
     * @return string
     */
    public function getNamespaceName(): string
    {
        return $this->namespace;
    }

    /**
     * Get the short name, the part without the namespace.
     * @return string
     */
    public function getShortName(): string
    {
        $this->node->name;
    }

    /**
     * @param Name $name
     * @return string
     */
    protected function resolveName(Name $name): string
    {
        $parts = $name->parts;
        if (!$name->isFullyQualified()) {
            /** @var NULL|UseUse $lastMatch */
            $lastMatch = null;
            foreach ($this->uses as $use) {
                foreach ($use->uses as $useuse) {
                    if ($useuse->alias === $parts[0]) {
                        $lastMatch = $useuse;
                    }
                }
            }
            if (null !== $lastMatch) {
                $parts[0] = join('\\', $lastMatch->name->parts);
            } else {
                if (!empty($this->namespace)) {
                    # No alias in 'use' matching, it's in the current namespace, if present
                    array_unshift($parts, $this->namespace);
                }
            }
        }
        return join('\\', $parts);
    }
}

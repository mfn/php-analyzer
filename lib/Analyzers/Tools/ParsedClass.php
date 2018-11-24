<?php
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

use Mfn\PHP\Analyzer\File;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_ as PhpParserClass;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Use_;

/**
 * Represent a class
 *
 * Use getInterfaces() to get the parent class or getInterfaces() to access the
 * interfaces.
 */
class ParsedClass extends ParsedObject implements Class_
{

    /** @var PhpParserClass */
    protected $node = null;
    /**
     * Full qualified parent name
     * @var NULL|string
     */
    private $fqParent = null;
    /** @var NULL|ParsedClass */
    private $parent = null;
    /**
     * The key is the fqn
     * @var ParsedInterface[]
     */
    private $implements = [];
    /** @var ParsedMethod[] */
    private $methods = null;

    /**
     * @param string $namespace
     * @param Use_[] $uses
     * @param PhpParserClass $class
     * @param File $file
     */
    public function __construct($namespace, array $uses, PhpParserClass $class, File $file)
    {
        $this->namespace = $namespace;
        $this->file = $file;
        $this->node = $class;
        $this->uses = $uses;
        $this->fqn = join('\\', array_filter([$namespace, $class->name]));
        $this->resolveNames();
        if (null === $this->fqParent && isset($class->extends)) {
            throw new \RuntimeException(
                'Something is amiss, resolved parent name and actual parent differ in ' .
                $file->getSplFile()->getRealPath() . ':' . $class->getLine()
            );
        }
        if (count($this->implements) !== count($class->implements)) {
            throw new \RuntimeException(
                'Something is amiss, resolved ' . count($this->implements) .
                ' interface(s) but found ' . count($class->implements) .
                ' actual interface(s) in ' .
                $file->getSplFile()->getRealPath() . ':' . $class->getLine()
            );
        }
    }

    /**
     * The "names" presented in "parent" and "interfaces" may not be fully
     * qualified; this steps resolved this
     */
    private function resolveNames()
    {
        if (isset($this->node->extends)) {
            $this->fqParent = $this->resolveName($this->node->extends);
        }
        foreach ($this->node->implements as $name) {
            $this->implements[$this->resolveName($name)] = null;
        }
    }

    /**
     * @return ParsedMethod[]
     */
    public function getMethods()
    {
        if (null === $this->methods) {
            $this->methods = array_map(
                function (ClassMethod $method) {
                    return new ParsedMethod($this, $method);
                },
                $this->node->getMethods()
            );
        }
        return $this->methods;
    }

    /**
     * @return Class_|NULL
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Class_ $parent
     * @return $this
     */
    public function setParent(Class_ $parent)
    {
        if ($parent->getName() !== $this->fqParent) {
            throw new \RuntimeException(
                'Expected class ' . $this->fqParent . ' but got ' . $parent->getName()
                . ' instead'
            );
        }
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Interface_[]
     */
    public function getInterfaces()
    {
        return array_values($this->implements);
    }

    /**
     * @param Interface_ $interface
     * @return $this
     */
    public function addInterface(Interface_ $interface)
    {
        if (!array_key_exists($interface->getName(), $this->implements)) {
            throw new \RuntimeException(
                'Class ' . $this->fqn . ' is not supposed to have the interface ' .
                $interface->getName()
            );
        }
        $this->implements[$interface->getName()] = $interface;
        return $this;
    }

    /**
     * @return NULL|string
     */
    public function getFqExtends()
    {
        return $this->fqParent;
    }

    /**
     * @return string[]
     */
    public function getInterfaceNames()
    {
        return array_keys($this->implements);
    }

    /**
     * Return an string identifier for this kind of object; no harm using
     * something human readable like 'Class', etc.
     *
     * @return string
     */
    public function getKind()
    {
        return 'Class';
    }

    /**
     * It returns the same object as getNode() but it's explicit about it's type
     *
     * @return PhpParserClass
     */
    public function getClass()
    {
        return $this->node;
    }

    /**
     * @return bool
     */
    public function isInterface()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isClass()
    {
        return true;
    }
}

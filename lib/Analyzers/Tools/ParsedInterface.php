<?php
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

use Mfn\PHP\Analyzer\File;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_ as PhpParserInterface;
use PhpParser\Node\Stmt\Use_;

/**
 * Represent an interface
 *
 * Use getInterfaces() to access the implemented interfaces.
 */
class ParsedInterface extends ParsedObject implements Interface_
{

    /** @var PhpParserInterface */
    protected $node = null;
    /**
     * The key is the fqn
     * @var ParsedInterface[]
     */
    private $extends = [];
    /** @var ParsedMethod[] */
    private $methods = null;

    /**
     * @param string $namespace
     * @param Use_[] $uses
     * @param PhpParserInterface $interface
     * @param File $file
     */
    public function __construct($namespace, array $uses, PhpParserInterface $interface, File $file)
    {
        $this->namespace = $namespace;
        $this->file = $file;
        $this->node = $interface;
        $this->uses = $uses;
        $this->fqn = join('\\', array_filter([$namespace, $interface->name]));
        $this->resolveNames();
        if (count($this->extends) !== count($interface->extends)) {
            throw new \RuntimeException(
                'Something is amiss, resolved ' . count($this->extends) .
                ' extend(s) but found ' . count($interface->extends) .
                ' actual extend(s) in ' .
                $file->getSplFile()->getRealPath() . ':' . $interface->getLine()
            );
        }
    }

    /**
     * The "names" presented in "extends"
     */
    private function resolveNames()
    {
        foreach ($this->node->extends as $name) {
            $this->extends[$this->resolveName($name)] = null;
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
                array_filter(
                    $this->node->stmts,
                    function ($stmt) {
                        return $stmt instanceof ClassMethod;
                    }
                )
            );
        }
        return $this->methods;
    }

    /**
     * @return ParsedInterface[]
     */
    public function getInterfaces()
    {
        return $this->extends;
    }

    /**
     * @param Interface_ $interface
     * @return $this
     */
    public function addInterface(Interface_ $interface)
    {
        if (!array_key_exists($interface->getName(), $this->extends)) {
            throw new \RuntimeException(
                'Interface ' . $this->fqn . ' is not supposed to have the interface ' .
                $interface->getName()
            );
        }
        $this->extends[$interface->getName()] = $interface;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getInterfaceNames()
    {
        return array_keys($this->extends);
    }

    /**
     * Return an string identifier for this kind of object; no harm using
     * something human readable like 'Class', etc.
     *
     * @return string
     */
    public function getKind()
    {
        return 'Interface';
    }

    /**
     * It returns the same object as getNode() but it's explicit about it's type
     *
     * @return PhpParserInterface
     */
    public function getInterface()
    {
        return $this->node;
    }

    /**
     * @return bool
     */
    public function isInterface()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isClass()
    {
        return false;
    }
}

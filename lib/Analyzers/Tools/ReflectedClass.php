<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

class ReflectedClass extends ReflectedObject implements Class_
{

    /** @var ReflectedInterface[] */
    private $interfaces = [];
    /** @var null|ReflectedClass */
    private $parent = null;

    public function __construct(\ReflectionClass $class)
    {
        if ($class->isInterface() || $class->isTrait()) {
            throw new \InvalidArgumentException('Only classes supported');
        }
        parent::__construct($class);
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

    /**
     * @return string
     */
    public function getKind()
    {
        return 'Class';
    }

    /**
     * @return Interface_[]
     */
    public function getInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * @return NULL|Class_
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Class_ $class
     * @return $this
     */
    public function setParent(Class_ $class)
    {
        if (!($class instanceof ReflectedClass)) {
            throw new \RuntimeException(
                'Only instances of ReflectedClass are supported'
            );
        }
        $this->parent = $class;
        return $this;
    }

    /**
     * @param Interface_ $interface
     * @return $this
     */
    public function addInterface(Interface_ $interface)
    {
        if (!($interface instanceof ReflectedInterface)) {
            throw new \RuntimeException(
                'Only instances of ReflectedInterface are supported'
            );
        }
        $this->interfaces[] = $interface;
        return $this;
    }
}

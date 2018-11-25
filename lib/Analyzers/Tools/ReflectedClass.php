<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

class ReflectedClass extends ReflectedObject implements Class_
{

    /** @var ReflectedInterface[] */
    private $interfaces = [];
    /** @var null|ReflectedClass */
    private $parent;

    public function __construct(\ReflectionClass $class)
    {
        if ($class->isInterface() || $class->isTrait()) {
            throw new \InvalidArgumentException('Only classes supported');
        }
        parent::__construct($class);
    }

    public function isInterface(): bool
    {
        return false;
    }

    public function isClass(): bool
    {
        return true;
    }

    public function getKind(): string
    {
        return 'Class';
    }

    /**
     * @return Interface_[]
     */
    public function getInterfaces(): array
    {
        return $this->interfaces;
    }

    /**
     * @return NULL|Class_
     */
    public function getParent(): ?Class_
    {
        return $this->parent;
    }

    /**
     * @param Class_ $class
     * @return $this
     */
    public function setParent(Class_ $class): Class_
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
    public function addInterface(Interface_ $interface): HasInterfaces
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

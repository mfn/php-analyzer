<?php
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

abstract class ReflectedObject implements GenericObject, Reflected
{

    /** @var \ReflectionClass */
    protected $reflectionClass;
    /** @var ReflectedMethod[] */
    protected $methods = null;

    public function __construct(\ReflectionClass $class)
    {
        $this->reflectionClass = $class;
    }

    public static function createFromReflectionClass(\ReflectionClass $class)
    {
        if ($class->isInterface()) {
            return new ReflectedInterface($class);
        } else {
            if ($class->isTrait()) {
                throw new \InvalidArgumentException('Traits are not supported');
            } else {
                return new ReflectedClass($class);
            }
        }
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass()
    {
        return $this->reflectionClass;
    }

    /**
     * @return ReflectedMethod[]
     */
    public function getMethods()
    {
        if (null === $this->methods) {
            $this->methods = [];
            foreach ($this->reflectionClass->getMethods() as $method) {
                $this->methods[] = new ReflectedMethod($this, $method);
            }
        }
        return $this->methods;
    }

    /**
     * Get the Full Qualified Name of the object
     * @return string
     */
    public function getName()
    {
        return $this->reflectionClass->getName();
    }

    /**
     * Get namespace name
     * @return string
     */
    public function getNamespaceName()
    {
        return $this->reflectionClass->getNamespaceName();
    }

    /**
     * Get the short name, the part without the namespace.
     * @return string
     */
    public function getShortName()
    {
        return $this->reflectionClass->getShortName();
    }


    /**
     * @return string[]
     */
    public function getInterfaceNames()
    {
        return $this->reflectionClass->getInterfaceNames();
    }
}

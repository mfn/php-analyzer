<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

class ReflectedMethod implements GenericMethod, Reflected
{

    /** @var ReflectedObject */
    private $object;
    /** @var  \ReflectionMethod */
    private $method;

    public function __construct(ReflectedObject $object, \ReflectionMethod $method)
    {
        $this->object = $object;
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->method->getName();
    }

    /**
     * Usually lower cased (because PHP is case-insensitive) so names can be
     * easier compared.
     * @return string
     */
    public function getNormalizedName()
    {
        return strtolower($this->method->getName());
    }

    /**
     * @return ReflectedObject
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return ReflectedInterface
     */
    public function getInterface()
    {
        if ($this->object instanceof ReflectedInterface) {
            return $this->object;
        }
        throw new \RuntimeException('Method is not part of an interface but of '
            . $this->object->getKind() . ' instead');
    }

    /**
     * @return ReflectedClass
     */
    public function getClass()
    {
        if ($this->object instanceof ReflectedClass) {
            return $this->object;
        }
        throw new \RuntimeException('Method is not part of a class but of '
            . $this->object->getKind() . ' instead');
    }
}

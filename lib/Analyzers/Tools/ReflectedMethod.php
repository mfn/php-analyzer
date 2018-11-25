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

    public function getName(): string
    {
        return $this->method->getName();
    }

    /**
     * Usually lower cased (because PHP is case-insensitive) so names can be
     * easier compared.
     * @return string
     */
    public function getNormalizedName(): string
    {
        return strtolower($this->method->getName());
    }

    /**
     * @return ReflectedObject
     */
    public function getObject(): GenericObject
    {
        return $this->object;
    }

    /**
     * @return ReflectedInterface
     */
    public function getInterface(): Interface_
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
    public function getClass(): Class_
    {
        if ($this->object instanceof ReflectedClass) {
            return $this->object;
        }
        throw new \RuntimeException('Method is not part of a class but of '
            . $this->object->getKind() . ' instead');
    }
}

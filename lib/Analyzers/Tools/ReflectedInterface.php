<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

class ReflectedInterface extends ReflectedObject implements Interface_
{

    /** @var ReflectedInterface[] */
    private $interfaces = [];

    public function __construct(\ReflectionClass $interface)
    {
        if (!$interface->isInterface() || $interface->isTrait()) {
            throw new \InvalidArgumentException('Only interfaces supported');
        }
        parent::__construct($interface);
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

    /**
     * @return string
     */
    public function getKind()
    {
        return 'Interface';
    }

    /**
     * @return Interface_[]
     */
    public function getInterfaces()
    {
        return $this->interfaces;
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

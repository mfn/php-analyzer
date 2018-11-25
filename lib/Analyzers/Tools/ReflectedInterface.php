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

    public function isInterface(): bool
    {
        return true;
    }

    public function isClass(): bool
    {
        return false;
    }

    public function getKind(): string
    {
        return 'Interface';
    }

    /**
     * @return Interface_[]
     */
    public function getInterfaces(): array
    {
        return $this->interfaces;
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

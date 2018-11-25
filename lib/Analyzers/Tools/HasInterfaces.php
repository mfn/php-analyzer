<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

interface HasInterfaces
{

    /**
     * @return Interface_[]
     */
    public function getInterfaces(): array;

    /**
     * @param Interface_ $interface
     * @return $this
     */
    public function addInterface(Interface_ $interface): HasInterfaces;

    /**
     * @return string[]
     */
    public function getInterfaceNames(): array;
}

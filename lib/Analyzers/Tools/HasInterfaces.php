<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

interface HasInterfaces
{

    /**
     * @return Interface_[]
     */
    public function getInterfaces();

    /**
     * @param Interface_ $interface
     * @return $this
     */
    public function addInterface(Interface_ $interface);

    /**
     * @return string[]
     */
    public function getInterfaceNames();
}

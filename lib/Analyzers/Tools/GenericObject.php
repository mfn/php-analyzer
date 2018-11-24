<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

/**
 * Represent any kind of class, interface or trait
 */
interface GenericObject
{

    /**
     * Get the Full Qualified Name of the object
     * @return string
     */
    public function getName();

    /**
     * Get namespace name
     * @return string
     */
    public function getNamespaceName();

    /**
     * Get the short name, the part without the namespace.
     * @return string
     */
    public function getShortName();

    /**
     * @return bool
     */
    public function isInterface();

    /**
     * @return bool
     */
    public function isClass();

    /**
     * @return GenericMethod[]
     */
    public function getMethods();

    /**
     * Returns what kind of object it is, i.e. 'Class', 'Interface', etc.
     * @return string
     */
    public function getKind();
}

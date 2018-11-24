<?php
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

interface GenericMethod
{

    /**
     * @return string
     */
    public function getName();

    /**
     * Usually lower cased (because PHP is case-insensitive) so names can be
     * easier compared.
     * @return string
     */
    public function getNormalizedName();

    /**
     * @return GenericObject
     */
    public function getObject();

    /**
     * @return Interface_
     */
    public function getInterface();

    /**
     * @return Class_
     */
    public function getClass();
}

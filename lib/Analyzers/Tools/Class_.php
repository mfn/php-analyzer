<?php
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

interface Class_ extends GenericObject, HasInterfaces
{

    /**
     * @return NULL|Class_
     */
    public function getParent();

    /**
     * @param Class_ $class
     * @return $this
     */
    public function setParent(Class_ $class);
}

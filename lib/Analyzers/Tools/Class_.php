<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

interface Class_ extends GenericObject, HasInterfaces
{

    public function getParent(): ?self;

    public function setParent(Class_ $class): self;
}

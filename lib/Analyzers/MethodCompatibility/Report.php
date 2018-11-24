<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\MethodCompatibility;

use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedMethod;

class Report extends \Mfn\PHP\Analyzer\Report\Report
{

    /** @var ParsedMethod */
    private $uberType;
    /** @var ParsedMethod */
    private $subType;

    public function __construct(
        ParsedMethod $uberType,
        ParsedMethod $subType
    ) {
        $this->uberType = $uberType;
        $this->subType = $subType;
    }

    public function report()
    {
        return sprintf(
            'Declaration of %s::%s must be compatible with %s::%s',
            $this->uberType->getInterface()->getName(),
            $this->uberType->getNameAndParamsSignature(),
            $this->subType->getInterface()->getName(),
            $this->subType->getNameAndParamsSignature()
        );
    }
}

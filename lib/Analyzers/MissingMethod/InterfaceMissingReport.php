<?php
namespace Mfn\PHP\Analyzer\Analyzers\MissingMethod;

use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedClass;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedMethod;
use Mfn\PHP\Analyzer\Report\Report;

class InterfaceMissingReport extends Report
{

    /** @var ParsedClass */
    private $class;
    /** @var ParsedMethod[] */
    private $methods;

    /**
     * @param ParsedClass $class The class which has missing abstract methods
     * @param ParsedMethod[] $methods The actual methods; they're coupled
     *                                      with their class better reporting.
     */
    public function __construct(ParsedClass $class, array $methods)
    {
        if (empty($methods)) {
            throw new \RuntimeException('At least one method must be present');
        }
        $this->class = $class;
        $this->methods = $methods;
    }

    public function report()
    {
        $msg = 'Class ' . $this->class->getName() . ' misses the following interface method';
        if (count($this->methods) > 1) {
            $msg .= 's';
        }
        $msg .= ': ';
        $lastInterface = null;
        $msg .= join(', ', array_map(function (ParsedMethod $cam) use (&$lastInterface) {
            $str = '';
            if ($lastInterface !== $cam->getInterface()) {
                $lastInterface = $cam->getInterface();
                $str .= $cam->getInterface()->getName();
            }
            return $str . '::' . $cam->getMethod()->name . '()';
        }, $this->methods));
        return $msg;
    }
}

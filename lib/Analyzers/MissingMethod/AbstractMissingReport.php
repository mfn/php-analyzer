<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\MissingMethod;

use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedClass;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedMethod;
use Mfn\PHP\Analyzer\Report\Report;

class AbstractMissingReport extends Report
{

    /** @var ParsedClass */
    private $class;
    /** @var ParsedMethod[] */
    private $methods;

    /**
     * @param ParsedClass $class The class which has missing abstract methods
     * @param ParsedMethod[] $methods The actual methods; they're coupled with
     *                                  their class defining them for better
     *                                  reporting.
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
        $msg = 'Class ' . $this->class->getName() . ' misses the following abstract method';
        if (count($this->methods) > 1) {
            $msg .= 's';
        }
        $msg .= ': ';
        $lastClass = null;
        $msg .= join(', ', array_map(function (ParsedMethod $cam) use (&$lastClass) {
            $str = '';
            if ($lastClass !== $cam->getClass()) {
                $lastClass = $cam->getClass();
                $str .= $cam->getClass()->getName();
            }
            return $str . '::' . $cam->getMethod()->name . '()';
        }, $this->methods));
        return $msg;
    }

    public function toArray()
    {
        return [
            'class' => $this->class->getName(),
            'methods' => array_map(
                function (ParsedMethod $cam) {
                    return
                        $cam->getClass()->getName() . '::' .
                        $cam->getMethod()->name . '()';
                },
                $this->methods
            )
        ] + parent::toArray();
    }
}

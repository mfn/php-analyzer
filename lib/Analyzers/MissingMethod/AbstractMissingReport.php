<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Markus Fischer <markus@fischer.name>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
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

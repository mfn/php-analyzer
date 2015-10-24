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
namespace Mfn\PHP\Analyzer\Analyzers\ObjectGraph;

use Mfn\PHP\Analyzer\Analyzers\Tools\Class_;
use Mfn\PHP\Analyzer\Analyzers\Tools\Interface_;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedClass;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedInterface;

/**
 * Provide useful helper methods on a parsed object graph
 */
class Helper
{

    /** @var  ObjectGraph */
    private $graph;

    public function __construct(ObjectGraph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * Checks if "$class instanceof $interface"
     *
     * @param NULL|Class_ $class
     * @param Interface_ $superInterface
     * @return bool
     */
    static public function classImplements(
        Class_ $class = null,
        Interface_ $superInterface
    ) {
        if (null === $class) {
            return false;
        }
        foreach ($class->getInterfaces() as $interface) {
            if ($interface === $superInterface) {
                return true;
            }
        }
        return self::classImplements($class->getParent(), $superInterface);
    }

    /**
     * Finds all classes extending the provided one
     *
     * @param ParsedClass $class
     * @param bool $recursive Whether to find all descendants; off by default
     * @return ParsedClass[]
     */
    public function findExtends(ParsedClass $class, $recursive = false)
    {
        $found = [];
        foreach ($this->graph->getClasses() as $object) {
            if ($class === $object->getParent()) {
                $found[] = $object;
                if ($recursive) {
                    $found = array_merge(
                        $found,
                        $this->findExtends($object, $recursive)
                    );
                }
            }
        }
        return $found;
    }

    /**
     * Finds all classes or interfaces implementing the provided one
     *
     * @param ParsedInterface $interface
     * @return ParsedInterface[]|ParsedClass[]
     */
    public function findImplements(ParsedInterface $interface)
    {
        $found = [];
        foreach ($this->graph->getObjects() as $object) {
            if ($object instanceof ParsedClass) {
                foreach ($object->getInterfaces() as $implements) {
                    if ($interface === $implements) {
                        $found[] = $object;
                        break;
                    }
                }
            } else {
                if ($object instanceof ParsedInterface) {
                    foreach ($object->getInterfaces() as $extends) {
                        if ($interface === $extends) {
                            $found[] = $object;
                            break;
                        }
                    }
                }
            }
        }
        return $found;
    }

    /**
     * Finds all interfaces implementing the provided one
     *
     * @param ParsedInterface $interface
     * @return ParsedInterface[]
     */
    public function findInterfaceImplements(ParsedInterface $interface)
    {
        $found = [];
        foreach ($this->graph->getInterfaces() as $object) {
            foreach ($object->getInterfaces() as $extends) {
                if ($interface === $extends) {
                    $found[] = $object;
                    break;
                }
            }
        }
        return $found;
    }
}

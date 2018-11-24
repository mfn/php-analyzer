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
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

abstract class ReflectedObject implements GenericObject, Reflected
{

    /** @var \ReflectionClass */
    protected $reflectionClass;
    /** @var ReflectedMethod[] */
    protected $methods = null;

    public function __construct(\ReflectionClass $class)
    {
        $this->reflectionClass = $class;
    }

    public static function createFromReflectionClass(\ReflectionClass $class)
    {
        if ($class->isInterface()) {
            return new ReflectedInterface($class);
        } else {
            if ($class->isTrait()) {
                throw new \InvalidArgumentException('Traits are not supported');
            } else {
                return new ReflectedClass($class);
            }
        }
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass()
    {
        return $this->reflectionClass;
    }

    /**
     * @return ReflectedMethod[]
     */
    public function getMethods()
    {
        if (null === $this->methods) {
            $this->methods = [];
            foreach ($this->reflectionClass->getMethods() as $method) {
                $this->methods[] = new ReflectedMethod($this, $method);
            }
        }
        return $this->methods;
    }

    /**
     * Get the Full Qualified Name of the object
     * @return string
     */
    public function getName()
    {
        return $this->reflectionClass->getName();
    }

    /**
     * Get namespace name
     * @return string
     */
    public function getNamespaceName()
    {
        return $this->reflectionClass->getNamespaceName();
    }

    /**
     * Get the short name, the part without the namespace.
     * @return string
     */
    public function getShortName()
    {
        return $this->reflectionClass->getShortName();
    }


    /**
     * @return string[]
     */
    public function getInterfaceNames()
    {
        return $this->reflectionClass->getInterfaceNames();
    }
}

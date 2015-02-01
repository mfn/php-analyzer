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

class ReflectedClass extends ReflectedObject implements Class_ {

  /** @var ReflectedInterface[] */
  private $interfaces = [];
  /** @var null|ReflectedClass */
  private $parent = NULL;

  public function __construct(\ReflectionClass $class) {
    if ($class->isInterface() || $class->isTrait()) {
      throw new \InvalidArgumentException('Only classes supported');
    }
    parent::__construct($class);
  }

  /**
   * @return bool
   */
  public function isInterface() {
    return false;
  }

  /**
   * @return bool
   */
  public function isClass() {
    return true;
  }

  /**
   * @return string
   */
  public function getKind() {
    return 'Class';
  }

  /**
   * @return Interface_[]
   */
  public function getInterfaces() {
    return $this->interfaces;
  }

  /**
   * @return NULL|Class_
   */
  public function getParent() {
    return $this->parent;
  }

  /**
   * @param Class_ $class
   * @return $this
   */
  public function setParent(Class_ $class) {
    if (!($class instanceof ReflectedClass)) {
      throw new \RuntimeException(
        'Only instances of ReflectedClass are supported'
      );
    }
    $this->parent = $class;
    return $this;
  }

  /**
   * @param Interface_ $interface
   * @return $this
   */
  public function addInterface(Interface_ $interface) {
    if (!($interface instanceof ReflectedInterface)) {
      throw new \RuntimeException(
        'Only instances of ReflectedInterface are supported'
      );
    }
    $this->interfaces[] = $interface;
    return $this;
  }
}

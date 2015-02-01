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

class ReflectedInterface extends ReflectedObject implements Interface_ {

  /** @var ReflectedInterface[] */
  private $interfaces = [];

  public function __construct(\ReflectionClass $interface) {
    if (!$interface->isInterface() || $interface->isTrait()) {
      throw new \InvalidArgumentException('Only interfaces supported');
    }
    parent::__construct($interface);
  }

  /**
   * @return bool
   */
  public function isInterface() {
    return true;
  }

  /**
   * @return bool
   */
  public function isClass() {
    return false;
  }

  /**
   * @return string
   */
  public function getKind() {
    return 'Interface';
  }

  /**
   * @return Interface_[]
   */
  public function getInterfaces() {
    return $this->interfaces;
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

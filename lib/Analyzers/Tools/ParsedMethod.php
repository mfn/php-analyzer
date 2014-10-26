<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Markus Fischer <markus@fischer.name>
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

use Mfn\PHP\Analyzer\Util\ParserPrinter;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\PrettyPrinter\Standard;

class ParsedMethod implements GenericMethod, Parsed {

  /** @var ClassMethod */
  private $method;
  /** @var Standard */
  private $printer;
  /** @var ParsedObject */
  private $object;

  /**
   * @param ParsedObject $object A Method always belongs to an object
   * @param ClassMethod $method
   */
  public function __construct(ParsedObject $object, ClassMethod $method) {
    $this->object = $object;
    $this->method = $method;
    $this->printer = new ParserPrinter();
  }

  public function getNormalizedName() {
    return strtolower($this->method->name);
  }

  /**
   * @return ClassMethod
   */
  public function getMethod() {
    return $this->method;
  }

  /**
   * @return ParsedClass
   */
  public function getClass() {
    if ($this->object instanceof ParsedClass) {
      return $this->object;
    }
    throw new \RuntimeException('Method is not part of a class but of '
      . $this->object->getKind() . ' instead');
  }

  /**
   * @return ParsedInterface
   */
  public function getInterface() {
    if ($this->object instanceof ParsedInterface) {
      return $this->object;
    }
    throw new \RuntimeException('Method is not part of an interface but of '
      . $this->object->getKind() . ' instead');
  }

  /**
   * @return string
   */
  public function getFullSignature() {
    return
      $this->printer->pModifiers($this->method)
      . 'function '
      . ($this->method->byRef ? '&' : '')
      . $this->getNameAndParamsSignature();
  }

  public function getNameAndParamsSignature() {
    return
      $this->method->name
      . '(' . $this->printer->expose_pImplode($this->method->params, ', ') . ')';
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->method->name;
  }

  /**
   * @return GenericObject
   */
  public function getObject() {
    // TODO: Implement getObject() method.
  }
}

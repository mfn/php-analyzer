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
namespace Mfn\PHP\Analyzer\Analyzers\MethodCompatibility;

use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedMethod;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * Generates signature/string representation of the method which allows it
 * easier to be compared to other methods if their parameters, types and other
 * properties match (except literal names).
 */
class MethodSignatureCompare {

  /** @var ParsedMethod */
  private $method;
  /** @var string */
  private $signature = '';

  public function __construct(ParsedMethod $method) {
    $this->method = $method;
    $this->signature = self::generateMethodSignature($method->getMethod());
  }

  /**
   * Generate a method signature for comparison with other methods for
   * compatibility. This means names of parameter types and other subtle things
   * are deliberately omitted from the signature.
   *
   * @param ClassMethod $method
   * @return string
   */
  static public function generateMethodSignature(ClassMethod $method) {
    $params = [];
    foreach ($method->params as $param) {
      $params[] = self::generateParamSignature($param);
    }
    return join(', ', $params);
  }

  static public function generateParamSignature(Param $param) {
    $names = [];
    if (isset($param->type)) {
      if ($param->type instanceof Name) {
        $names[] = $param->type->toString();
      } else {
        $names[] = $param->type;
      }
    }
    $names[] = $param->byRef ? '&$' : '$';
    if (isset($param->default)) {
      $names[] = '=';
    }
    return join(' ', $names);
  }

  /**
   * @return ParsedMethod
   */
  public function getMethod() {
    return $this->method;
  }

  /**
   * @return string
   */
  public function getSignature() {
    return $this->signature;
  }
}

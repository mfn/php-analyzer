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
namespace Mfn\PHP\Analyzer\Analyzers\CakePHP2\QueryConditionVariables;

use PhpParser\Node;
use PhpParser\NodeVisitor;

/**
 * Find all 'Variable' statements; to be used from QueryConditionVariables
 */
class Variables implements NodeVisitor {

  /** @var Node\Expr\Variable[] */
  private $variables = [];

  /**
   * Called once before traversal.
   *
   * Return value semantics:
   *  * null:      $nodes stays as-is
   *  * otherwise: $nodes is set to the return value
   *
   * @param Node[] $nodes Array of nodes
   *
   * @return null|Node[] Array of nodes
   */
  public function beforeTraverse(array $nodes) {
    $this->variables = [];
  }

  /**
   * Called when entering a node.
   *
   * Return value semantics:
   *  * null:      $node stays as-is
   *  * otherwise: $node is set to the return value
   *
   * @param Node $node Node
   *
   * @return null|Node Node
   */
  public function enterNode(Node $node) {
    if ($node instanceof Node\Expr\Variable) {
      $this->variables[] = $node;
    }
  }

  /**
   * Called when leaving a node.
   *
   * Return value semantics:
   *  * null:      $node stays as-is
   *  * false:     $node is removed from the parent array
   *  * array:     The return value is merged into the parent array (at the position of the $node)
   *  * otherwise: $node is set to the return value
   *
   * @param Node $node Node
   *
   * @return null|Node|false|Node[] Node
   */
  public function leaveNode(Node $node) {
    // TODO: Implement leaveNode() method.
  }

  /**
   * Called once after traversal.
   *
   * Return value semantics:
   *  * null:      $nodes stays as-is
   *  * otherwise: $nodes is set to the return value
   *
   * @param Node[] $nodes Array of nodes
   *
   * @return null|Node[] Array of nodes
   */
  public function afterTraverse(array $nodes) {
    // TODO: Implement afterTraverse() method.
  }

  /**
   * @return Node\Expr\Variable[]
   */
  public function getVariables() {
    return $this->variables;
  }
}

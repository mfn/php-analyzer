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
namespace Mfn\PHP\Analyzer\Analyzers\ExceptionEmptyCatch;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\NodeVisitor;

class NonCommentStatementCollector implements NodeVisitor {

  /**
   * @var int
   */
  private $nonCommentStatements = 0;

  /**
   * @return int
   */
  public function getNonCommentStatements() {
    return $this->nonCommentStatements;
  }

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
    $this->nonCommentStatements = 0;
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
    if ($node instanceof Comment) {
      return;
    }
    $this->nonCommentStatements++;
  }

  public function leaveNode(Node $node) {
  }

  public function afterTraverse(array $nodes) {
  }
}

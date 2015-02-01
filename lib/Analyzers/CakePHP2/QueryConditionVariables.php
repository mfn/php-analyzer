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
namespace Mfn\PHP\Analyzer\Analyzers\CakePHP2;

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Analyzers\CakePHP2\QueryConditionVariables\Variables;
use Mfn\PHP\Analyzer\Analyzers\Severity;
use Mfn\PHP\Analyzer\File;
use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Report\Lines;
use Mfn\PHP\Analyzer\Report\SourceFragment;
use Mfn\PHP\Analyzer\Report\StringReport;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;

/**
 * Find CakePHP2 query `conditions` arrays which use variable interpolation.
 *
 * Emits a warning for every occurrence where `conditions` key (for querying
 * models) is found and uses variable interpolation in its statements.
 *
 * This helps checking those parts and ensure these variables, which are one
 * source of SQL injections, are properly escaped.
 *
 * Limitations:
 * - cannot detect whether the variable is properly escaped, thus
 *   the warnings are always generated which limits its usefulness.
 * - does not support nested `conditions`
 */
class QueryConditionVariables extends Analyzer implements NodeVisitor {

  /** @var File */
  private $currentFile = NULL;
  /** @var Project */
  private $project = NULL;
  /** @var NodeTraverser */
  private $subNodeTraverser = NULL;
  /** @var Variables */
  private $variablesVisitor = NULL;
  /** @var Variable[] */
  private $variables = NULL;

  public function __construct() {
    $this->setSeverity(Severity::WARNING);
    $this->subNodeTraverser = new NodeTraverser();
    $this->variablesVisitor = new Variables();
    $this->subNodeTraverser->addVisitor($this->variablesVisitor);
  }

  /**
   * @return string
   */
  public function getName() {
    return 'CakePHP2 QueryConditionVariables';
  }

  /**
   * @param Project $project
   */
  public function analyze(Project $project) {
    $this->project = $project;
    $traverser = new NodeTraverser();
    $traverser->addVisitor($this);
    foreach ($project->getFiles() as $file) {
      $this->currentFile = $file;
      $traverser->traverse($file->getTree());
      foreach ($this->variables as $variable) {
        $report = new StringReport(
          'Variable used in constructing raw SQL, is it escaped?');
        $line = $variable->getAttribute('startLine') - 1;
        $report->setSourceFragment(
          new SourceFragment(
            $file,
            new Lines(
              $line - $this->sourceContext,
              $line + $this->sourceContext,
              $line
            )
          )
        );
        $project->addReport($report);
      }
    }
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
    if ($node instanceof Node\Expr\ArrayItem) {
      $key = $node->key;
      $value = $node->value;
      if (
        $key instanceof Node\Scalar\String
        &&
        $key->value === 'conditions'
        &&
        $value instanceof Node\Expr\Array_
      ) {
        foreach ($value->items as $item) {
          if (NULL !== $item->key) {
            continue;
          }
          $value = $item->value;
          if (!($value instanceof Node\Expr\BinaryOp\Concat)) {
            continue;
          }
          $this->subNodeTraverser->traverse([$value]);
          $this->variables = array_merge(
            $this->variables,
            $this->variablesVisitor->getVariables()
          );
        }
      }
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
  public
  function leaveNode(Node $node) {
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
  public
  function afterTraverse(array $nodes) {
    // TODO: Implement afterTraverse() method.
  }
}

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
namespace Mfn\PHP\Analyzer\Analyzers;

use Mfn\PHP\Analyzer\File;
use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Report\Lines;
use Mfn\PHP\Analyzer\Report\SourceFragment;
use Mfn\PHP\Analyzer\Report\StringReport;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;

/**
 * Report all occurrences of dynamic class invocation, e.g. `new $foo`.
 *
 * Those *can be* a source of bad design and hint for refactoring; this analyzer
 * reports all source code fragments contain dynamic class instantiation.
 */
class DynamicClassInstantiation extends Analyzer implements NodeVisitor {

  /** @var File */
  private $currentFile = NULL;
  /** @var Project */
  private $project = NULL;

  public function __construct() {
    $this->setSeverity(Severity::WARNING);
  }

  /**
   * @return string
   */
  public function getName() {
    return 'DynamicClassInstantiation';
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
    // TODO: Implement beforeTraverse() method.
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
    if ($node instanceof New_) {
      if ($node->class instanceof Variable) {
        $msg = 'Dynamic class instantiation with variable ';
        if ($node->class->name instanceof Variable) {
          $msg .= 'variable $' . $node->class->name->name;
        } else {
          $msg .= '$' . $node->class->name;
        }
        $report = new StringReport(
          $msg . ' in '
          . $this->currentFile->getSplFile()->getFilename() . ':'
          . $node->class->getLine()
        );
        $report->setSourceFragment(
          new SourceFragment(
            $this->currentFile,
            new Lines(
              $node->class->getAttribute('startLine') - 1 - $this->sourceContext,
              $node->class->getAttribute('endLine') - 1 + $this->sourceContext,
              $node->class->getAttribute('startLine') - 1
            )
          )
        );
        $this->project->addReport($report);
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
}

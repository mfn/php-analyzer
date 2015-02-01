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

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Analyzers\Severity;
use Mfn\PHP\Analyzer\File;
use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Report\Lines;
use Mfn\PHP\Analyzer\Report\SourceFragment;
use Mfn\PHP\Analyzer\Report\StringReport;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;

/**
 * Find all empty exception catch blocks
 *
 * They're often a block hole of confusion because they literally swallow the
 * actual error and make it really hard to errors. They're one of the top-most
 * bad practices. Empty catch blocks are by default emitted as warnings.
 */
class ExceptionEmptyCatch extends Analyzer implements NodeVisitor {

  /**
   * @var Project
   */
  private $project;
  /**
   * @var NonCommentStatementCollector
   */
  private $nonCommentCounterVisitor;
  /**
   * @var File
   */
  private $currentFile;

  /**
   *
   */
  public function __construct() {
    $this->setSeverity(Severity::WARNING);
    $this->subNodeTraverser = new NodeTraverser();
    $this->nonCommentCounterVisitor = new NonCommentStatementCollector();
    $this->subNodeTraverser->addVisitor($this->nonCommentCounterVisitor);
  }

  /**
   * @return string
   */
  public function getName() {
    return 'ExceptionEmptyCatchBlock';
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

  public function beforeTraverse(array $nodes) {
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
    if ($node instanceof Node\Stmt\Catch_) {
      $this->subNodeTraverser->traverse($node->stmts);
      $nonComments = $this->nonCommentCounterVisitor->getNonCommentStatements();
      if (0 === $nonComments) {
        $report = new StringReport('Empty catch block found');
        $line = $node->getAttribute('startLine') - 1;
        $report->setSourceFragment(
          new SourceFragment(
            $this->currentFile,
            new Lines(
              $line - $this->sourceContext,
              $line + $this->sourceContext,
              $line
            )
          )
        );
        $this->project->addReport($report);
      }
    }
  }

  public function leaveNode(Node $node) {
  }

  public function afterTraverse(array $nodes) {
  }
}

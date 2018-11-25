<?php declare(strict_types=1);
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
class ExceptionEmptyCatch extends Analyzer implements NodeVisitor
{

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
    /** NodeTraverser */
    private $subNodeTraverser;

    /**
     *
     */
    public function __construct()
    {
        $this->setSeverity(Severity::WARNING);
        $this->subNodeTraverser = new NodeTraverser();
        $this->nonCommentCounterVisitor = new NonCommentStatementCollector();
        $this->subNodeTraverser->addVisitor($this->nonCommentCounterVisitor);
    }

    public function getName(): string
    {
        return 'ExceptionEmptyCatchBlock';
    }

    public function analyze(Project $project): void
    {
        $this->project = $project;
        $traverser = new NodeTraverser();
        $traverser->addVisitor($this);
        foreach ($project->getFiles() as $file) {
            $this->currentFile = $file;
            $traverser->traverse($file->getTree());
        }
    }

    public function beforeTraverse(array $nodes)
    {
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
    public function enterNode(Node $node)
    {
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

    public function leaveNode(Node $node)
    {
    }

    public function afterTraverse(array $nodes)
    {
    }
}

<?php declare(strict_types=1);
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
class QueryConditionVariables extends Analyzer implements NodeVisitor
{

    /** @var File */
    private $currentFile;
    /** @var Project */
    private $project;
    /** @var NodeTraverser */
    private $subNodeTraverser;
    /** @var Variables */
    private $variablesVisitor;
    /** @var Variable[] */
    private $variables;

    public function __construct()
    {
        $this->setSeverity(Severity::WARNING);
        $this->subNodeTraverser = new NodeTraverser();
        $this->variablesVisitor = new Variables();
        $this->subNodeTraverser->addVisitor($this->variablesVisitor);
    }

    public function getName(): string
    {
        return 'CakePHP2 QueryConditionVariables';
    }

    public function analyze(Project $project): void
    {
        $this->project = $project;
        $traverser = new NodeTraverser();
        $traverser->addVisitor($this);
        foreach ($project->getFiles() as $file) {
            $this->currentFile = $file;
            $traverser->traverse($file->getTree());
            foreach ($this->variables as $variable) {
                $report = new StringReport(
                    'Variable used in constructing raw SQL, is it escaped?'
                );
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
    public function beforeTraverse(array $nodes)
    {
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
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\ArrayItem) {
            $key = $node->key;
            $value = $node->value;
            if (
                $key instanceof Node\Scalar\String_
                &&
                $key->value === 'conditions'
                &&
                $value instanceof Node\Expr\Array_
            ) {
                foreach ($value->items as $item) {
                    if (null !== $item->key) {
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
    public function leaveNode(
        Node $node
    ) {
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
    public function afterTraverse(
        array $nodes
    ) {
        // TODO: Implement afterTraverse() method.
    }
}

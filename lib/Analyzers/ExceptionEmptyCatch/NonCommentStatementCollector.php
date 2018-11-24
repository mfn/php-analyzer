<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\ExceptionEmptyCatch;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\NodeVisitor;

class NonCommentStatementCollector implements NodeVisitor
{

    /**
     * @var int
     */
    private $nonCommentStatements = 0;

    /**
     * @return int
     */
    public function getNonCommentStatements()
    {
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
    public function beforeTraverse(array $nodes)
    {
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
    public function enterNode(Node $node)
    {
        if ($node instanceof Comment) {
            return;
        }
        $this->nonCommentStatements++;
    }

    public function leaveNode(Node $node)
    {
    }

    public function afterTraverse(array $nodes)
    {
    }
}

<?php
namespace Mfn\PHP\Analyzer\Analyzers;

use Mfn\PHP\Analyzer\Project;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver as PhpParserNameResolver;

/**
 * Runs the nikic/PhpParser `NameResolver`
 *
 * This is usually run after the `Parser` analyzer.
 *
 * The purpose is to have the PhpParser NameResolver run which will throw
 * exception on duplicate defined names; this ensures further Analyzers
 * have at least these things already covered.
 */
class NameResolver extends Analyzer
{

    /**
     * @param Project $project
     */
    public function analyze(Project $project)
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new PhpParserNameResolver());
        foreach ($project->getFiles() as $file) {
            $traverser->traverse($file->getTree());
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'NameResolver';
    }
}

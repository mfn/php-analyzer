<?php
namespace Mfn\PHP\Analyzer\Analyzers;

use Mfn\PHP\Analyzer\File;
use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Report\FileParserErrorReport;
use Mfn\PHP\Analyzer\Report\StringReport;
use PhpParser\Error;

/**
 * Parses the PHP source files into an AST and add them to the project.
 *
 * This should usually be your first analyzer.
 *
 * It expects all files to be scanned to be added to the `Project` already
 * (`\SplFileInfo`) and parses them using the nikic/PhpParser library.
 */
class Parser extends Analyzer
{

    /** @var \PhpParser\Parser */
    private $parser;

    public function __construct(\PhpParser\Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param Project $project
     */
    public function analyze(Project $project)
    {
        $logger = $project->getLogger();
        foreach ($project->getSplFileInfos() as $splFileInfo) {
            try {
                $source = file($splFileInfo->getRealPath());
                $code = join('', $source);
            } catch (\RuntimeException $e) {
                $project->addReport(new StringReport($e->getMessage()));
                continue;
            }
            try {
                $logger->info('Parsing ' . $splFileInfo->getRealPath());
                $tree = $this->parser->parse($code);
                $project->addFile(new File($splFileInfo, $source, $tree));
            } catch (Error $e) {
                $project->getLogger()->warning(
                    '[' . $this->getName() . '] ' .
                    'Error while parsing ' . $splFileInfo->getRealPath() . ' : ' .
                    $e->getMessage()
                );
                $project->addReport(new FileParserErrorReport($splFileInfo, $e));
            }
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Parser';
    }
}

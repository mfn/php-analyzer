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

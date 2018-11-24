#!/usr/bin/env php
<?php
/**
 * Automatically generate analyzers markdown doc from classes
 */

use Mfn\PHP\Analyzer\Analyzers\NameResolver;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\Helper;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\ObjectGraph;
use Mfn\PHP\Analyzer\Analyzers\Parser;
use Mfn\PHP\Analyzer\Logger\Stdout;
use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Util\Util;
use PhpParser\Lexer;

require_once __DIR__ . '/../vendor/autoload.php';
error_reporting(E_ALL);
Util::installMinimalError2ExceptionHandler();

$projectRealPath = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..');

# Use analyzer to gather the object graph
$project = new Project(new Stdout());
$project->addSplFileInfos(Util::scanDir(__DIR__ . '/../lib/'));
$project->addAnalyzers([
    new Parser(new \PhpParser\Parser(new Lexer())),
    new NameResolver(),
    $objectGraph = new ObjectGraph()
]);
$project->analyze();

$helper = new Helper($objectGraph);
$className = 'Mfn\PHP\Analyzer\Analyzers\Analyzer';
$class = $objectGraph->getClassByFqn($className);
if (null === $class) {
    throw new \RuntimeException("Unable to find class $className");
}
unset($className);
$descendants = $helper->findExtends($class, true);
sort($descendants);

/** @var string[] $index */
$index = [];
$index[] = '# Built-in / available Analyzers';
$index[] = '';
foreach ($descendants as $class) {
    $doccomment = $class->getClass()->getDocComment();
    if (null === $doccomment) {
        continue;
    }

    $text = preg_split('/\R/', $doccomment->getReformattedText());
    if (($len = count($text)) < 3) {
        continue;
    }

    # Split doc comment into lines with some massaging
    $text = array_filter(
        array_map(
            function ($line) { # extract content
                return preg_replace('/^\s*\*\s?/', '', $line);
            },
            array_filter(
                $text = array_slice($text, 1, $len - 2),
                function ($line) { # only lines with actual content
                    return preg_match('/^\s*\*/', $line);
                }
            )
        ),
        function ($line) { # filter out annotations
            return !preg_match('/^@/', $line);
        }
    );

    $relClassFilname = str_replace($projectRealPath, '',
        $class->getFile()->getSplFile()->getRealPath());

    $text = array_merge(
        [
            '#### Class [' . $class->getName() . '](' . $relClassFilname . ')',
            '',
        ],
        $text,
        [
            '',
        ]
    );

    $index = array_merge($index, $text);
}

file_put_contents(
    __DIR__ . '/../doc/analyzers.md',
    join("\n", $index) . "\n"
);

#!/usr/bin/env php
<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Markus Fischer <markus@fischer.name>
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
if (NULL === $class) {
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
  if (NULL === $doccomment) {
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

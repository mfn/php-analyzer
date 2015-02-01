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
namespace Mfn\PHP\Analyzer\Console;

use Mfn\PHP\Analyzer\Analyzers\NameResolver;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\Graphviz as GraphvizConverter;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\ObjectGraph;
use Mfn\PHP\Analyzer\Analyzers\Parser;
use Mfn\PHP\Analyzer\Logger\Logger;
use Mfn\PHP\Analyzer\Logger\SymfonyConsoleOutput;
use Mfn\PHP\Analyzer\Project;
use PhpParser\Lexer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Graphviz extends Command {

  protected function configure() {
    $this
      ->setName('graphviz')
      ->setDescription(
        'Generate class relationship diagram for graphviz (.dot file)')
      ->addOption(
        'namespaces',
        NULL,
        InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
        'Provide whitelist of namespaces to include (you can use `/` instead of `\\`)'
      )
      ->addOption(
        'show-namespace',
        NULL,
        InputOption::VALUE_NONE,
        'Show namespaces in labels'
      )
      ->addOption(
        'show-only-connected',
        NULL,
        InputOption::VALUE_NONE,
        'Only show objects which are connected to others'
      )
      ->addOption(
        'cluster-namespaces',
        NULL,
        InputOption::VALUE_NONE,
        'Cluster objects by their namespace'
      )
      ->addOption(
        'nest-clusters',
        NULL,
        InputOption::VALUE_NONE,
        'Nest namespace clusters inside each other'
      );
    SourceHandler::configure($this);
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    # If possible log to stderr because stdout receives the dot file
    $logger = new SymfonyConsoleOutput(
      $output instanceof ConsoleOutput ? $output->getErrorOutput() : $output
    );
    $project = new Project($logger);

    if ($input->getOption('quiet')) {
      $logger->setReportingLevel(Logger::ERROR);
    } else if ($input->getOption('verbose')) {
      $logger->setReportingLevel(Logger::INFO);
    }

    SourceHandler::addSourcesToProject($input, $project);

    # Necessary setup to generate the objectGraph
    $project->addAnalyzer(new Parser(new \PhpParser\Parser(new Lexer())));
    $project->addAnalyzer(new NameResolver());
    $project->addAnalyzer($objectGraph = new ObjectGraph());
    $project->analyze();

    $graphviz = new GraphvizConverter();
    $graphviz->setGraph($objectGraph);
    # Console configuration
    $graphviz->setNamespaceWhitelist($input->getOption('namespaces'));
    $graphviz->setShowNamespace($input->getOption('show-namespace'));
    $graphviz->setShowOnlyConnected($input->getOption('show-only-connected'));
    $graphviz->setClusterByNamespace($input->getOption('cluster-namespaces'));
    $graphviz->setNestClusters($input->getOption('nest-clusters'));

    $output->write($graphviz->generate());
  }
}

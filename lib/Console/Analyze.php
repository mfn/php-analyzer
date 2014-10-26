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
namespace Mfn\PHP\Analyzer\Console;

use Mfn\PHP\Analyzer\Listener\Json;
use Mfn\PHP\Analyzer\Listener\Plain;
use Mfn\PHP\Analyzer\Logger\Logger;
use Mfn\PHP\Analyzer\Logger\SymfonyConsoleOutput;
use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Report\Formatter\Json as JsonFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Analyze extends Command {

  protected function configure() {
    $this
      ->setName('analyze')
      ->setDescription('Run the analyzer on the provided sources')
      ->addOption('report', NULL, InputOption::VALUE_REQUIRED,
        'Write report to file (stdout otherwise)')
      ->addOption('format', NULL, InputOption::VALUE_REQUIRED,
        'Format of the report file: "plain" (default), "json" or "json-pretty"')
      ->addOption('config', NULL, InputOption::VALUE_REQUIRED,
        'Load configuration of analyzers from this file. This is expected to be '
        . 'a plain PHP file which returns an array of Analyzers.');
    SourceHandler::configure($this);
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $logger = new SymfonyConsoleOutput($output);
    $project = new Project($logger);

    if ($input->getOption('quiet')) {
      $logger->setReportingLevel(Logger::ERROR);
    } else if ($input->getOption('verbose')) {
      $logger->setReportingLevel(Logger::INFO);
    }

    # Fall back to UTC if date.timezone is not set
    $dateTimezone = ini_get('date.timezone');
    if (empty($dateTimezone)) {
      $logger->warning('date.timezone not set, falling back to UTC');
      date_default_timezone_set('UTC');
    }

    SourceHandler::addSourcesToProject($input, $project);

    $analyzers = NULL !== $input->getOption('config')
      ? require $input->getOption('config')
      : Project::getDefaultConfig();

    # Configure listener
    $listener = NULL;
    if (NULL !== $input->getOption('report')) {

      $format = NULL === $input->getOption('format')
        ? 'plain'
        : $input->getOption('format');

      if ('plain' === $format) {
        $listener = new Plain(fopen($input->getOption('report'), 'w'));
      } else if (0 === strpos($format, 'json')) {
        $options = 0;
        if ('json-pretty' === $format) {
          $options = JSON_PRETTY_PRINT;
        }
        $json = new JsonFormatter($options);
        $listener = new Json(fopen($input->getOption('report'), 'w'), $json);
      } else {
        throw new \RuntimeException("Unsupported report format '$format'");
      }

    } else {
      if (!$input->getOption('quiet')) {
        $listener = new Plain(\STDOUT);
      }
    }
    if (NULL !== $listener) {
      $project->addListener($listener);
    }

    $project->addAnalyzers($analyzers);
    $project->analyze();
    $analyzerReports = $project->getAnalyzerReports();

    if (count($analyzerReports) > 0) {
      return 1;
    } else {
      $output->writeln('Nothing found to report.');
      return 0;
    }
  }
}

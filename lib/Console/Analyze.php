<?php declare(strict_types=1);
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

class Analyze extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('analyze')
            ->setDescription('Run the analyzer on the provided sources')
            ->addOption(
                'report',
                null,
                InputOption::VALUE_REQUIRED,
                'Write report to file (stdout otherwise)'
            )
            ->addOption(
                'format',
                null,
                InputOption::VALUE_REQUIRED,
                'Format of the report file: "plain" (default), "json" or "json-pretty"'
            )
            ->addOption(
                'config',
                null,
                InputOption::VALUE_REQUIRED,
                'Load configuration of analyzers from this file. This is expected to be '
                . 'a plain PHP file which returns an array of Analyzers.'
            );
        SourceHandler::configure($this);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $logger = new SymfonyConsoleOutput($output);
        $project = new Project($logger);

        if ($input->getOption('quiet')) {
            $logger->setReportingLevel(Logger::ERROR);
        } else {
            if ($input->getOption('verbose')) {
                $logger->setReportingLevel(Logger::INFO);
            }
        }

        # Fall back to UTC if date.timezone is not set
        $dateTimezone = ini_get('date.timezone');
        if (empty($dateTimezone)) {
            $logger->warning('date.timezone not set, falling back to UTC');
            date_default_timezone_set('UTC');
        }

        SourceHandler::addSourcesToProject($input, $project);

        $analyzers = null !== $input->getOption('config')
            ? require $input->getOption('config')
            : Project::getDefaultConfig();

        # Configure listener
        $listener = null;
        if (null !== $input->getOption('report')) {
            $format = null === $input->getOption('format')
                ? 'plain'
                : $input->getOption('format');

            if ('plain' === $format) {
                $listener = new Plain(fopen($input->getOption('report'), 'w'));
            } else {
                if (0 === strpos($format, 'json')) {
                    $options = 0;
                    if ('json-pretty' === $format) {
                        $options = JSON_PRETTY_PRINT;
                    }
                    $json = new JsonFormatter($options);
                    $listener = new Json(fopen($input->getOption('report'), 'w'), $json);
                } else {
                    throw new \RuntimeException("Unsupported report format '$format'");
                }
            }
        } else {
            if (!$input->getOption('quiet')) {
                $listener = new Plain(\STDOUT);
            }
        }
        if (null !== $listener) {
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

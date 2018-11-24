<?php declare(strict_types=1);
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

class Graphviz extends Command
{
    protected function configure()
    {
        $this
            ->setName('graphviz')
            ->setDescription(
                'Generate class relationship diagram for graphviz (.dot file)'
            )
            ->addOption(
                'namespaces',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Provide whitelist of namespaces to include (you can use `/` instead of `\\`)'
            )
            ->addOption(
                'show-namespace',
                null,
                InputOption::VALUE_NONE,
                'Show namespaces in labels'
            )
            ->addOption(
                'show-only-connected',
                null,
                InputOption::VALUE_NONE,
                'Only show objects which are connected to others'
            )
            ->addOption(
                'cluster-namespaces',
                null,
                InputOption::VALUE_NONE,
                'Cluster objects by their namespace'
            )
            ->addOption(
                'nest-clusters',
                null,
                InputOption::VALUE_NONE,
                'Nest namespace clusters inside each other'
            );
        SourceHandler::configure($this);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        # If possible log to stderr because stdout receives the dot file
        $logger = new SymfonyConsoleOutput(
            $output instanceof ConsoleOutput ? $output->getErrorOutput() : $output
        );
        $project = new Project($logger);

        if ($input->getOption('quiet')) {
            $logger->setReportingLevel(Logger::ERROR);
        } else {
            if ($input->getOption('verbose')) {
                $logger->setReportingLevel(Logger::INFO);
            }
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

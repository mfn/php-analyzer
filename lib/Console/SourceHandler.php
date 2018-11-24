<?php
namespace Mfn\PHP\Analyzer\Console;

use Mfn\PHP\Analyzer\Project;
use Mfn\PHP\Analyzer\Util\Util;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Common Symfony Console code used by multiple commands.
 */
class SourceHandler
{

    /** @var \SplFileInfo[] */
    protected $splFiles = [];

    public static function configure(Command $command)
    {
        $command
            ->addOption(
                'load-from',
                null,
                InputOption::VALUE_REQUIRED,
                'Additional file with list of files/directories to scan.'
            )
            ->addArgument(
                'sources',
                InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                'Files/directories to scan'
            );
    }

    public static function addSourcesToProject(InputInterface $input, Project $project)
    {
        $inputSources = [];
        if (null !== $input->getOption('load-from')) {
            $inputSources = array_merge(
                $inputSources,
                array_map('trim', file($input->getOption('load-from')))
            );
        }
        if (null !== $input->getArgument('sources')) {
            $inputSources = array_merge(
                $inputSources,
                $input->getArgument('sources')
            );
        }
        $inputSources = array_unique($inputSources);
        if (empty($inputSources)) {
            throw new \RuntimeException('No input sources/directories for scanning provided.');
        }

        /** @var \SplFileInfo[] $files */
        $files = [];
        foreach ($inputSources as $inputSource) {
            if (is_dir($inputSource)) {
                $files = array_merge($files, Util::scanDir($inputSource));
            } else {
                if (is_file($inputSource)) {
                    $files[] = new \SplFileInfo($inputSource);
                } else {
                    throw new \RuntimeException(
                        "File $inputSource is not a file nor a directory."
                    );
                }
            }
        }
        if (count($files) === 0) {
            throw new \RuntimeException('No source files to scan found');
        }
        foreach ($files as $file) {
            $project->addSplFileInfo($file);
        }
    }
}

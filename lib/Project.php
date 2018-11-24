<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer;

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Listener\Listener;
use Mfn\PHP\Analyzer\Logger\ProjectLogger;
use Mfn\PHP\Analyzer\Report\AnalyzerReport;
use Mfn\PHP\Analyzer\Report\Report;
use Mfn\PHP\Analyzer\Report\TimestampedReport;
use Mfn\Util\Map\SimpleOrderedValidatingMap as Map;
use Mfn\Util\Map\SimpleOrderedValidatingMapException as MapException;

/**
 * The Project is the central piece.
 *
 * Add files and analyzers to it and run analyze() . Retrieve the reports with
 * getReports()
 */
class Project
{

    /**
     * Parsed files
     * @var File[]
     */
    private $files = [];
    /**
     * Unparsed files
     * @var \SplFileInfo[]
     */
    private $splFileInfos = [];
    /** @var Analyzer[] */
    private $analyzers = [];
    /** @var Map map<Analyzer,TimestampedReport[]> */
    private $reports = null;
    /** @var AnalyzerReport[] */
    private $analyzerReports = [];
    /** @var NULL|Analyzer */
    private $currentAnalyzer = null;
    /** @var ProjectLogger */
    private $logger = null;
    /** @var Listener[] */
    private $listeners = [];

    public function __construct(ProjectLogger $logger)
    {
        $this->logger = $logger;
        $this->reports = new Map(
            function ($key) {
                if ($key instanceof Analyzer) {
                    return;
                }
                throw new MapException('Only keys of type Analyzer are accepted');
            },
            function ($value) {
                if (is_array($value)) {
                    return;
                }
                throw new MapException('Only values of type array are accepted');
            }
        );
    }

    /**
     * Returns the default configuration (analyzers)
     *
     * @return Analyzer[]
     */
    public static function getDefaultConfig()
    {
        return require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
            . 'res/defaultAnalyzerConfiguration.php';
    }

    /**
     * @param \SplFileInfo[] $files
     * @return $this
     */
    public function addSplFileInfos(array $files)
    {
        foreach ($files as $file) {
            $this->addSplFileInfo($file);
        }
        return $this;
    }

    /**
     * @param \SplFileInfo $file
     * @return $this
     */
    public function addSplFileInfo(\SplFileInfo $file)
    {
        $this->logger->info('Adding ' . $file->getRealPath());
        $this->splFileInfos[] = $file;
        return $this;
    }

    /**
     * @return \SplFileInfo[]
     */
    public function getSplFileInfos()
    {
        return $this->splFileInfos;
    }

    /**
     * @param File $file
     * @return $this
     */
    public function addFile(File $file)
    {
        $this->files[] = $file;
        return $this;
    }

    /**
     * Add a report
     *
     * Internally, two representations of the reports are kept:
     * - one groups the analyzers with it's respective reports ($this->report)
     * - the other maintains a flat list of a analyzer/report tuple
     *   ($this->analyzerReports)
     *
     * The latter is dispatched to the listeners.
     *
     * @param Report $report
     * @return $this
     */
    public function addReport(Report $report)
    {
        if (!($this->currentAnalyzer instanceof Analyzer)) {
            throw new \RuntimeException(
                'Only reports with a currently active Analyzer can be added'
            );
        }
        # store in $this->reports ; grouped by Analyzer
        $reports = [];
        if ($this->reports->exists($this->currentAnalyzer)) {
            $reports = $this->reports->get($this->currentAnalyzer);
        }
        $reports[] = $timestampedReport = new TimestampedReport($report);
        $this->reports->set($this->currentAnalyzer, $reports);
        # store analyzer/report tuple in $this->analyzerReports
        $this->analyzerReports[] = $analyzerReport = new AnalyzerReport(
            $this->currentAnalyzer,
            $timestampedReport
        );
        $this->notifyAddReport($analyzerReport);
        return $this;
    }

    private function notifyAddReport(AnalyzerReport $report)
    {
        foreach ($this->listeners as $listener) {
            $listener->addReport($report);
        }
    }

    /**
     * @param Analyzer[] $analyzers
     * @return $this
     */
    public function addAnalyzers(array $analyzers)
    {
        foreach ($analyzers as $analyzer) {
            $this->addAnalyzer($analyzer);
        }
        return $this;
    }

    /**
     * @param Analyzer $analyzer
     * @return $this
     */
    public function addAnalyzer(Analyzer $analyzer)
    {
        $this->analyzers[] = $analyzer;
        return $this;
    }

    /**
     * Main entry point
     * @return $this
     */
    public function analyze()
    {
        $this->notifyProjectStart();
        foreach ($this->analyzers as $analyzer) {
            $this->currentAnalyzer = $analyzer;
            $this->logger->setActiveAnalyzerName($analyzer->getName());
            $this->notifyBeforeAnalyzer($analyzer);
            $analyzer->analyze($this);
            $this->notifyAfterAnalyzer($analyzer);
        }
        $this->currentAnalyzer = null;
        $this->logger->setActiveAnalyzerName('');
        $this->notifyProjectEnd();
        return $this;
    }

    private function notifyProjectStart()
    {
        foreach ($this->listeners as $listener) {
            $listener->projectStart($this);
        }
    }

    private function notifyBeforeAnalyzer(Analyzer $analyzer)
    {
        foreach ($this->listeners as $listener) {
            $listener->beforeAnalyzer($analyzer);
        }
    }

    private function notifyAfterAnalyzer(Analyzer $analyzer)
    {
        foreach ($this->listeners as $listener) {
            $listener->afterAnalyzer($analyzer);
        }
    }

    private function notifyProjectEnd()
    {
        foreach ($this->listeners as $listener) {
            $listener->projectEnd($this);
        }
    }

    /**
     * @return ProjectLogger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Sets a logger instance on the object
     *
     * @param ProjectLogger $logger
     * @return $this
     */
    public function setLogger(ProjectLogger $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return AnalyzerReport[]
     */
    public function getAnalyzerReports()
    {
        return $this->analyzerReports;
    }

    /**
     * @param Listener $listener
     * @return $this
     */
    public function addListener(Listener $listener)
    {
        $this->listeners[] = $listener;
        return $this;
    }

    /**
     * @param Listener $listener
     * @return $this
     */
    public function removeListener(Listener $listener)
    {
        $pos = array_search($listener, $this->listeners, true);
        if (false !== $pos) {
            unset($this->listeners[$pos]);
        }
        return $this;
    }
}

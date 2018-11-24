<?php
namespace Mfn\PHP\Analyzer;

use Mfn\PHP\Analyzer\Listener\Phing as PhingListener;
use Mfn\PHP\Analyzer\Logger\Phing;
use PhpParser\Lexer;

class PhingTask extends \Task
{

    /** @var \FileSet[] */
    private $filesets;
    /** @var bool */
    private $haltonerror = true;
    /** @var bool */
    private $haltonwarning = false;
    /** @var \PhingFile */
    private $logfile = null;
    /**
     * The format of the logfile. Supported formats:
     * - 'plain' text (default)
     * - 'json'
     * @var string
     */
    private $logFormat = 'plain';
    /** @var \PhingFile */
    private $configFile = null;

    /**
     * @return boolean
     */
    public function isHaltonerror()
    {
        return $this->haltonerror;
    }

    /**
     * @param boolean $haltonerror
     */
    public function setHaltonerror($haltonerror)
    {
        if (!is_bool($haltonerror)) {
            throw new \InvalidArgumentException(
                'Attribute haltonerror is a boolean attribute and requires true/false'
            );
        }
        $this->haltonerror = $haltonerror;
    }

    /**
     * @return boolean
     */
    public function isHaltonwarning()
    {
        return $this->haltonwarning;
    }

    /**
     * @param boolean $haltonwarning
     */
    public function setHaltonwarning($haltonwarning)
    {
        if (!is_bool($haltonwarning)) {
            throw new \InvalidArgumentException(
                'Attribute haltonwarning is a boolean attribute and requires true/false'
            );
        }
        $this->haltonwarning = $haltonwarning;
    }

    public function addFileSet(\FileSet $fs)
    {
        $this->filesets[] = $fs;
    }

    public function init()
    {
        $dateTimezone = ini_get('date.timezone');
        if (empty($dateTimezone)) {
            date_default_timezone_set('UTC');
            $this->log(
                'date.timezone not set, falling back to UTC',
                \Project::MSG_WARN
            );
        }
    }

    public function main()
    {
        if (null === $this->filesets) {
            throw new \BuildException('No fileset provided');
        }

        $project = new Project(new Phing($this));
        $project->addListener($listener = new PhingListener($this));

        $analyzers = null !== $this->getConfigFile()
            ? require $this->getConfigFile()
            : Project::getDefaultConfig();
        $project->addAnalyzers($analyzers);

        # Add files
        foreach ($this->filesets as $fs) {
            $ds = $fs->getDirectoryScanner($this->project);
            /** @var \PhingFile $fromDir */
            $fromDir = $fs->getDir($this->project);
            /** @var  $files */
            $files = $ds->getIncludedFiles();
            foreach ($files as $file) {
                $fileName = $fromDir->getAbsolutePath() . DIRECTORY_SEPARATOR . $file;
                $this->log('Adding file ' . $fileName, \Project::MSG_VERBOSE);
                $project->addSplFileInfo(new \SplFileInfo($fileName));
            }
        }

        $project->analyze();

        $buildErrorMessage = $listener->getBuildErrorMessage();
        if (!empty($buildErrorMessage)) {
            throw new \BuildException($buildErrorMessage);
        }
    }

    /**
     * @return \PhingFile
     */
    public function getConfigFile()
    {
        return $this->configFile;
    }

    /**
     * @param \PhingFile $configFile
     * @return $this
     */
    public function setConfigFile($configFile)
    {
        $this->configFile = $configFile;
        return $this;
    }

    /**
     * @return \PhingFile
     */
    public function getLogfile()
    {
        return $this->logfile;
    }

    /**
     * @param \PhingFile $logfile
     */
    public function setLogfile(\PhingFile $logfile)
    {
        $this->logfile = $logfile;
    }

    /**
     * @return string
     */
    public function getLogFormat()
    {
        return $this->logFormat;
    }

    /**
     * @param string $logFormat
     * @return $this
     */
    public function setLogFormat($logFormat)
    {
        $this->logFormat = $logFormat;
        return $this;
    }
}

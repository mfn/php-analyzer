<?php
namespace Mfn\PHP\Analyzer\Analyzers;

use Mfn\PHP\Analyzer\Project;

abstract class Analyzer
{

    /**
     * How many lines of source context before/after
     * @var int
     */
    protected $sourceContext = 2;
    /**
     * One of the Severity:: constants
     * @var int
     */
    private $severity = Severity::ERROR;

    /**
     * @return int
     */
    public function getSourceContext()
    {
        return $this->sourceContext;
    }

    /**
     * @param int $sourceContext
     * @return $this
     */
    public function setSourceContext($sourceContext)
    {
        $this->sourceContext = $sourceContext;
        return $this;
    }

    /**
     * Returns the current severity
     *
     * @return integer
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * @param int $severity
     * @return $this
     */
    public function setSeverity($severity)
    {
        $this->severity = $severity;
        return $this;
    }

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @param Project $project
     */
    abstract public function analyze(Project $project);
}

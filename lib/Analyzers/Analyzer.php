<?php declare(strict_types=1);
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
    public function getSourceContext(): int
    {
        return $this->sourceContext;
    }

    /**
     * @param int $sourceContext
     * @return $this
     */
    public function setSourceContext($sourceContext): self
    {
        $this->sourceContext = $sourceContext;
        return $this;
    }

    /**
     * Returns the current severity
     *
     * @return integer
     */
    public function getSeverity(): int
    {
        return $this->severity;
    }

    /**
     * @param int $severity
     * @return $this
     */
    public function setSeverity($severity): self
    {
        $this->severity = $severity;
        return $this;
    }

    abstract public function getName(): string;

    abstract public function analyze(Project $project): void;
}

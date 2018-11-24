<?php
namespace Mfn\PHP\Analyzer\Logger;

abstract class ProjectLogger extends Logger
{

    /** @var string */
    private $activeAnalyzerName = '';

    public function log($level, $message, array $context = [])
    {
        $level = self::ensureLevelIsvalidInt($level);
        if ($level >= $this->reportingLevel) {
            if (0 !== strlen($this->activeAnalyzerName)) {
                $message = sprintf('[%s] %s', $this->activeAnalyzerName, $message);
            }
            $this->realLog($level, $message, $context);
        }
        return null;
    }

    /**
     * @param string $activeAnalyzerName
     * @return $this
     */
    public function setActiveAnalyzerName($activeAnalyzerName)
    {
        $this->activeAnalyzerName = $activeAnalyzerName;
        return $this;
    }
}

<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Logger;

/**
 * Translates our log calls to Phing system.
 */
class Phing extends ProjectLogger
{

    /** @var \Task */
    private $task;

    public function __construct(\Task $phingProject)
    {
        $this->task = $phingProject;
    }

    public function log($level, $message, array $context = [])
    {
        $level = $this->ensureLevelIsvalidInt($level);
        # we let phing make the decisions which levels to log and which not
        $this->realLog($level, $message);
    }


    protected function realLog($level, $message, array $context = []): void
    {
        $message = self::interpolateContext($message, $context);
        switch ($level) {
            case self::DEBUG:
                $level = \Project::MSG_DEBUG;
                break;
            case self::INFO:
                $level = \Project::MSG_VERBOSE;
                break;
            case self::NOTICE:
                $level = \Project::MSG_VERBOSE;
                break;
            case self::WARNING:
                $level = \Project::MSG_WARN;
                break;
            case self::ERROR:
            case self::CRITICAL:
            case self::ALERT:
            case self::EMERGENCY:
                $level = \Project::MSG_ERR;
                break;
            default:
                $level = \Project::MSG_INFO;
        }
        $this->task->log($message, $level);
        return null;
    }
}

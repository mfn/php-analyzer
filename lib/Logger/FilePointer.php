<?php
namespace Mfn\PHP\Analyzer\Logger;

class FilePointer extends ProjectLogger
{

    /**
     * File descriptor to log to
     * @var resource
     */
    private $fd;

    /**
     * Initialize logger with file pointer to log to
     *
     * @param resource $fd An already opened file pointer to log to.
     */
    public function __construct($fd)
    {
        if (!is_resource($fd)) {
            throw new \InvalidArgumentException(
                'Argument $fp must be of type resource, ' . gettype($fd) . ' given'
            );
        }
        $this->fd = $fd;
    }

    protected function realLog($level, $message, array $context = [])
    {
        $message = self::interpolateContext($message, $context);
        fwrite(
            $this->fd,
            sprintf(
                '[%s] %s' . PHP_EOL,
                self::levelToString($level),
                $message
            )
        );
        return null;
    }
}

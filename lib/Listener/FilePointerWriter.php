<?php
namespace Mfn\PHP\Analyzer\Listener;

abstract class FilePointerWriter implements Listener
{

    /** @var resource */
    private $fd;

    /**
     * @param resource $fd File descriptor
     */
    public function __construct($fd)
    {
        if (!is_resource($fd)) {
            throw new \InvalidArgumentException(
                'Argument $fd must be of type resource, ' . gettype($fd) . ' given'
            );
        }
        $this->fd = $fd;
    }

    protected function write($msg)
    {
        fwrite($this->fd, $msg);
    }
}

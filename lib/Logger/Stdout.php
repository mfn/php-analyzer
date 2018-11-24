<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Logger;

class Stdout extends FilePointer
{
    public function __construct()
    {
        if (!defined('STDOUT')) {
            throw new \RuntimeException('Constant STDOUT not available');
        }
        parent::__construct(\STDOUT);
    }
}

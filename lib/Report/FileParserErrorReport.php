<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Report;

use PhpParser\Error;

class FileParserErrorReport extends StringReport
{

    /** @var  \SplFileInfo */
    private $file;
    /** @var  Error */
    private $error;

    /**
     * @param \SplFileInfo $file
     * @param Error $error
     */
    public function __construct(\SplFileInfo $file, Error $error)
    {
        $this->file = $file;
        $this->error = $error;
        $this->message = sprintf(
            '%s in %s',
            $this->error->getMessage(),
            $this->file->getRealPath()
        );
    }
}

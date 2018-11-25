<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer;

/**
 * Represent a complete source file, including the source and the parsed
 * statement tree.
 */
class File
{

    /**
     * @var \SplFileInfo
     */
    private $splFile;
    /**
     * @var string[]
     */
    private $source;
    /**
     * @var \PhpParser\Node[]
     */
    private $tree;

    /**
     * @param \SplFileInfo $splFile
     * @param string[] $source As returned from file() ; expected to include EOL
     * @param \PhpParser\Node[] $tree
     */
    public function __construct(\SplFileInfo $splFile, array $source, array $tree)
    {
        $this->splFile = $splFile;
        $this->source = $source;
        $this->tree = $tree;
    }

    /**
     * @return \SplFileInfo
     */
    public function getSplFile(): \SplFileInfo
    {
        return $this->splFile;
    }

    /**
     * @return string[]
     */
    public function getSource(): array
    {
        return $this->source;
    }

    /**
     * @return \PhpParser\Node[]
     */
    public function getTree(): array
    {
        return $this->tree;
    }
}

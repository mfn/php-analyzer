<?php
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
    private $splFile = null;
    /**
     * @var string[]
     */
    private $source = null;
    /**
     * @var \PhpParser\Node[]
     */
    private $tree = null;

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
    public function getSplFile()
    {
        return $this->splFile;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return \PhpParser\Node[]
     */
    public function getTree()
    {
        return $this->tree;
    }
}

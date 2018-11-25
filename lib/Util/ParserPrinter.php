<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Util;

use PhpParser\Node;
use PhpParser\PrettyPrinter\Standard;

class ParserPrinter extends Standard
{

    /**
     * Pretty prints an array of nodes and implodes the printed values.
     *
     * @param Node[] $nodes Array of Nodes to be printed
     * @param string $glue Character to implode with
     *
     * @return string Imploded pretty printed nodes
     */
    public function expose_pImplode(array $nodes, $glue = ''): string
    {
        return $this->pImplode($nodes, $glue);
    }
}

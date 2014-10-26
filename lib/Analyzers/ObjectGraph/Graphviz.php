<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Markus Fischer <markus@fischer.name>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Mfn\PHP\Analyzer\Analyzers\ObjectGraph;

use Mfn\PHP\Analyzer\Analyzers\Tools\Interface_;
use Mfn\PHP\Analyzer\Analyzers\Tools\Parsed;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedClass;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedInterface;

/**
 * Generate a graphviz compatible `.dot` file with generate()
 *
 * Usage:
 * - create new instance
 * - add graph with `->setGraph($objectGraph)`
 * - call `->generate()`, it will return a string
 *
 * You can tune various behaviours how the graph is generated, please have a
 * look at the getters.
 */
class Graphviz {

  /**
   * Whitelist of namespaces to include
   * @var string[]
   */
  private $namespaceWhitelist = [];
  /**
   * Show namespaces in labels
   * @var bool
   */
  private $showNamespace = false;
  /**
   * Only include nodes in graph which have relations to other nodes
   * @var bool
   */
  private $showOnlyConnected = false;
  /** @var ObjectGraph */
  private $graph;
  /** @var bool */
  private $clusterByNamespace = false;
  /** @var bool */
  private $nestClusters = false;

  /**
   * @return string
   */
  public function generate() {
    $nodes = [];
    $edges = [];
    $mapping = $this->graph->getObjects();
    $fqns = array_keys($mapping);
    $objects = array_values($mapping);
    $markNodeReferenced = [];
    $addEdge = function ($from, $to) use (&$edges, &$markNodeReferenced) {
      $edges[] = sprintf('%d -> %d', $from, $to);
      $markNodeReferenced[$from] = true;
      $markNodeReferenced[$to] = true;
    };
    $clusters = []; # Array of namespaces containing a list of nodes
    foreach ($objects as $index => $object) {

      # In case object from Reflections are available, filter them out
      if (!($object instanceof Parsed)) {
        continue;
      }

      if (!empty($this->namespaceWhitelist)) {
        $fqn = strtolower($object->getFqn());
        $match = false;
        foreach ($this->namespaceWhitelist as $namespace) {
          if (0 === strpos($fqn, $namespace)) {
            $match = true;
            break;
          }
        }
        if (!$match) {
          continue;
        }
      }

      if ($this->clusterByNamespace) {
        $namespaceName = $object->getNamespaceName();
        if (!empty($namespaceName)) {
          if (!isset($clusters[$namespaceName])) {
            $clusters[$namespaceName] = [
              'nodes'        => [],
              'subgraphs'    => [],
              'alreadyDrawn' => false,
            ];
          }
          $clusters[$namespaceName]['nodes'][] = $index;
        }
      }

      $shape = $object instanceof Interface_ ? 'shape=diamond' : '';

      # we add them by their index to be able to remove them if showOnlyConnected
      $nodes[$index] = sprintf('%d [ label = "%s"; %s ]',
        $index,
        $this->showNamespace
          ? str_replace('\\', '\\\\', $object->getName())
          : $object->getNode()->name,
        $shape
      );

      if ($object instanceof ParsedClass) {
        $fqExtends = $object->getFqExtends();
        if (NULL !== $fqExtends) {
          if (false !== $pos = array_search($fqExtends, $fqns, true)) {
            $addEdge($index, $pos);
          }
        }
        $fqImplements = $object->getInterfaceNames();
        foreach ($fqImplements as $fqImplement) {
          if (false !== $pos = array_search($fqImplement, $fqns, true)) {
            $addEdge($index, $pos);
          }
        }

      } else if ($object instanceof ParsedInterface) {
        $fqExtends = $object->getInterfaceNames();
        foreach ($fqExtends as $fqExtend) {
          if (false !== $pos = array_search($fqExtend, $fqns, true)) {
            $addEdge($index, $pos);
          }
        }

      } else {
        throw new \RuntimeException('Unsupported object ' . get_class($object));
      }
    }

    if ($this->showOnlyConnected) {
      $markNodeReferenced = array_keys($markNodeReferenced);
      foreach ($nodes as $index => $label) {
        if (!in_array($index, $markNodeReferenced)) {
          unset($nodes[$index]);
        }
      }
    }

    $out = ['digraph {'];
    $out[] = 'rankdir = "RL"';
    $out[] = 'node[shape=record]';

    if ($this->clusterByNamespace) {

      # Remove clusters without nodes; this can happen due showOnlyConnected
      foreach ($clusters as $clusterName => &$clusterData) {
        $clusterNodes = array_filter($clusterData['nodes'],
          function ($node) use ($nodes) {
            return isset($nodes[$node]);
          }
        );
        if (empty($clusterNodes)) {
          unset($clusters[$clusterName]);
        } else {
          $clusterData['nodes'] = $clusterNodes;
        }
      }

      if ($this->nestClusters) {
        # Handling nested graphs is tricky because they need to be literally
        # nested in the dot file too:
        # - reverse sort namespaces
        # - for each namespace, see if you a matching parent namespace and add it
        ksort($clusters);
        $clusterNames = array_keys($clusters);
        rsort($clusterNames);
        foreach ($clusterNames as $index => $clusterName) {
          foreach (array_slice($clusterNames, $index + 1) as $tryingMatchName) {
            $nameToMatch = $tryingMatchName . '\\';
            if (0 === strpos($clusterName, $nameToMatch)) {
              $clusters[$tryingMatchName]['subgraphs'][] = $clusterName;
              break;
            }
          }
        }
      }

      $clusterIndex = 0; # counter .dot file
      $drawCluster = function ($name) use (&$clusters, &$out, &$drawCluster, &$clusterIndex) {
        $cluster = $clusters[$name];
        if ($cluster['alreadyDrawn']) {
          return;
        }
        $clusters[$name]['alreadyDrawn'] = true;
        $out[] = sprintf('subgraph cluster_%d {', $clusterIndex);
        $out[] = sprintf('label = "%s";', str_replace('\\', '\\\\', $name));
        # subgraphs are actually only filled if nestClusters is true
        foreach ($cluster['subgraphs'] as $subgraphName) {
          $drawCluster($subgraphName);
        }
        foreach ($cluster['nodes'] as $node) {
          $out[] = $node . ';';
        }
        $out[] = '}';
        $clusterIndex++;
      };

      foreach (array_keys($clusters) as $clusterName) {
        $drawCluster($clusterName);
      }
    }

    $out = array_merge($out, $nodes);
    $out = array_merge($out, $edges);
    $out[] = '}';
    return join("\n", $out) . "\n";
  }

  /**
   * @return string[]
   */
  public function getNamespaceWhitelist() {
    return $this->namespaceWhitelist;
  }

  /**
   * @param string[] $namespaces
   * @return $this
   */
  public function setNamespaceWhitelist(array $namespaces) {
    $this->namespaceWhitelist = [];
    foreach ($namespaces as $namespace) {
      $this->addNamespaceToWhitelist($namespace);
    }
    return $this;
  }

  /**
   * Normalize: lower case and ensure backslashes
   * @param string $namespace
   * @return $this
   */
  public function addNamespaceToWhitelist($namespace) {
    $this->namespaceWhitelist = strtolower(str_replace('/', '\\', $namespace));
    return $this;
  }

  /**
   * @return boolean
   */
  public function isShowNamespace() {
    return $this->showNamespace;
  }

  /**
   * @param boolean $showNamespace
   * @return $this
   */
  public function setShowNamespace($showNamespace) {
    $this->showNamespace = $showNamespace;
    return $this;
  }

  /**
   * @return ObjectGraph
   */
  public function getGraph() {
    return $this->graph;
  }

  /**
   * @param ObjectGraph $graph
   * @return $this
   */
  public function setGraph($graph) {
    $this->graph = $graph;
    return $this;
  }

  /**
   * @return boolean
   */
  public function isShowOnlyConnected() {
    return $this->showOnlyConnected;
  }

  /**
   * @param boolean $showOnlyConnected
   * @return $this
   */
  public function setShowOnlyConnected($showOnlyConnected) {
    $this->showOnlyConnected = $showOnlyConnected;
    return $this;
  }

  /**
   * @return boolean
   */
  public function isClusterByNamespace() {
    return $this->clusterByNamespace;
  }

  /**
   * @param boolean $clusterByNamespace
   * @return $this
   */
  public function setClusterByNamespace($clusterByNamespace) {
    $this->clusterByNamespace = $clusterByNamespace;
    return $this;
  }

  /**
   * @return boolean
   */
  public function isNestClusters() {
    return $this->nestClusters;
  }

  /**
   * @param boolean $nestClusters
   * @return $this
   */
  public function setNestClusters($nestClusters) {
    $this->nestClusters = $nestClusters;
    return $this;
  }
}

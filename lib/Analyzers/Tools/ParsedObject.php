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
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

use Mfn\PHP\Analyzer\File;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;

/**
 * Represent the base for a  class or interface.
 */
abstract class ParsedObject implements GenericObject, Parsed {

  /**
   * Full qualified name
   * @var string
   */
  protected $fqn;
  /** @var string */
  protected $namespace = '';
  /** @var File */
  protected $file;
  /** @var Use_[] */
  protected $uses = [];
  /** @var Node */
  protected $node = NULL;

  /**
   * @return File
   */
  public function getFile() {
    return $this->file;
  }

  /**
   * @return Node
   */
  public function getNode() {
    return $this->node;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->fqn;
  }

  /**
   * Return an string identifier for this kind of object; no harm using
   * something human readable like 'Class', 'Interface', etc.
   *
   * @return string
   */
  abstract public function getKind();

  /**
   * Get namespace name
   * @return string
   */
  public function getNamespaceName() {
    return $this->namespace;
  }

  /**
   * Get the short name, the part without the namespace.
   * @return string
   */
  public function getShortName() {
    $this->node->name;
  }

  /**
   * @param Name $name
   * @return string
   */
  protected function resolveName(Name $name) {
    $parts = $name->parts;
    if (!$name->isFullyQualified()) {
      /** @var NULL|UseUse $lastMatch */
      $lastMatch = NULL;
      foreach ($this->uses as $use) {
        foreach ($use->uses as $useuse) {
          if ($useuse->alias === $parts[0]) {
            $lastMatch = $useuse;
          }
        }
      }
      if (NULL !== $lastMatch) {
        $parts[0] = join('\\', $lastMatch->name->parts);
      } else if (!empty($this->namespace)) {
        # No alias in 'use' matching, it's in the current namespace, if present
        array_unshift($parts, $this->namespace);
      }
    }
    return join('\\', $parts);
  }
}

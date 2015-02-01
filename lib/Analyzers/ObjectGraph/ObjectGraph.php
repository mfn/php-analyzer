<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Markus Fischer <markus@fischer.name>
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

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Analyzers\Tools\Class_;
use Mfn\PHP\Analyzer\Analyzers\Tools\GenericObject;
use Mfn\PHP\Analyzer\Analyzers\Tools\Interface_;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedClass;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedInterface;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedObject;
use Mfn\PHP\Analyzer\Analyzers\Tools\ReflectedClass;
use Mfn\PHP\Analyzer\Analyzers\Tools\ReflectedInterface;
use Mfn\PHP\Analyzer\Analyzers\Tools\ReflectedObject;
use Mfn\PHP\Analyzer\File;
use Mfn\PHP\Analyzer\Project;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_ as PhpParserClass;
use PhpParser\Node\Stmt\Interface_ as PhpParserInterface;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;

/**
 * Builds a graph of all the object (classes, interfaces) and "connects" them
 * together, allowing the object tree to be further analysed.
 *
 * It also includes full qualified name resolution for relevant names; this
 * may be obsolete due using PhpParser\NodeVisitor\NameResolver but it's using
 * it's own implementation for now.
 *
 * You will often encounter the term "fqn" which stands for "full qualified name".
 * A full qualified name includes the namespace, i.e. "My\Name\Spaced\Class". A
 * fqn **does not** include the leading backslash `\`.
 *
 *
 * Various methods exist to access the objects (once the tree is built):
 * - `getObjectByFqn()`
 * - `getClassByFqn()`
 * - `getInterfaceByFqn()`
 * - ... many more
 *
 * The ObjectGraph uses `nikic/PhpParser` to collect classes/interfaces and wraps
 * those in own `Class_` or `Interface_` objects which provide further methods
 * to navigate around the object, e.g. they project methods to access the
 * parent class with `Class_::getInterfaces`, etc.
 */
class ObjectGraph extends Analyzer implements NodeVisitor {

  /** @var GenericObject[] */
  private $objects = [];

  # From here on, NodeVisitor runtime properties
  /** @var string */
  private $currentNamespace = '';
  /** @var Use_[] */
  private $currentUseStatements = [];
  /** @var File */
  private $currentFile = NULL;

  /** @var Project */
  private $project = NULL;

  public function analyze(Project $project) {
    $this->project = $project;
    $logger = $project->getLogger();
    $traverser = new NodeTraverser();
    $traverser->addVisitor($this);
    foreach ($project->getFiles() as $file) {
      $logger->info('Traversing ' . $file->getSplFile()->getRealPath());
      $this->currentFile = $file;
      $traverser->traverse($file->getTree());
    }
    $this->resolveGraph();
  }

  /**
   * Resolves object references within the graph
   *
   * Call this once you added new objects to the graph to have them resolved
   * or their references resolved in previously added objects.
   */
  public function resolveGraph() {
    foreach ($this->objects as $fqn => $object) {

      if ($object instanceof ParsedClass) {

        $fqExtends = $object->getFqExtends();
        if (NULL !== $fqExtends) {
          $extends = $this->getClassByFqnInRelation($object, $fqExtends);
          if (NULL !== $extends) {
            $object->setParent($extends);
          }
        }
        foreach ($object->getInterfaceNames() as $fqImplements) {
          $implements = $this->getInterfaceByFqnInRelation($object, $fqImplements);
          if (NULL !== $implements) {
            $object->addInterface($implements);
          }
        }

      } else if ($object instanceof ParsedInterface) {

        foreach ($object->getInterfaceNames() as $fqExtends) {
          $extends = $this->getInterfaceByFqnInRelation($object, $fqExtends);
          if (NULL !== $extends) {
            $object->addInterface($extends);
          }
        }

      } else if ($object instanceof ReflectedClass) {

        $class = $object->getReflectionClass();

        if (false !== $parent = $class->getParentClass()) {
          $extends = $this->getClassByFqnInRelation($object, $parent->getName());
          $object->setParent($extends);
        }

        foreach ($class->getInterfaceNames() as $fqImplements) {
          $implements = $this->getInterfaceByFqnInRelation($object, $fqImplements);
          $object->addInterface($implements);
        }

      } else if ($object instanceof ReflectedInterface) {

        $interface = $object->getReflectionClass();

        foreach ($interface->getInterfaceNames() as $fqExtends) {
          $extends = $this->getInterfaceByFqnInRelation($object, $fqExtends);
          $object->addInterface($extends);
        }

      } else {
        throw new \RuntimeException(
          'Unsupported object of type ' . get_class($object));
      }
    }
  }

  /**
   * @param GenericObject $fromObject
   * @param string $fqn
   * @return NULL|ParsedClass
   */
  private function getClassByFqnInRelation(GenericObject $fromObject, $fqn) {
    $class = $this->getObjectByFqn($fqn);
    if (NULL !== $class && !($class instanceof Class_)) {
      throw new ObjectTypeMissmatchException($fromObject, $class, 'Class');
    }
    return $class;
  }

  /**
   * @param string $fqn
   * @return NULL|GenericObject
   */
  public function getObjectByFqn($fqn) {
    if (!isset($this->objects[$fqn])) {
      return NULL;
    }
    return $this->objects[$fqn];
  }

  /**
   * @param GenericObject $fromObject
   * @param string $fqn
   * @return NULL|ParsedInterface
   */
  private function getInterfaceByFqnInRelation(GenericObject $fromObject, $fqn) {
    $class = $this->getObjectByFqn($fqn);
    if (NULL !== $class && !($class instanceof Interface_)) {
      throw new ObjectTypeMissmatchException($fromObject, $class, 'Interface');
    }
    return $class;
  }

  /**
   * @return string
   */
  public function getName() {
    return 'ObjectGraph';
  }

  /**
   * @param string $fqn
   * @return NULL|ParsedClass
   */
  public function getClassByFqn($fqn) {
    if (!isset($this->objects[$fqn])) {
      return NULL;
    }
    $class = $this->objects[$fqn];
    if (!($class instanceof ParsedClass)) {
      return NULL;
    }
    return $class;
  }

  /**
   * @param string $fqn
   * @return NULL|ParsedInterface
   */
  public function getInterfaceByFqn($fqn) {
    if (!isset($this->objects[$fqn])) {
      return NULL;
    }
    $interface = $this->objects[$fqn];
    if (!($interface instanceof ParsedInterface)) {
      return NULL;
    }
    return $interface;
  }

  /**
   * @return ParsedObject[]
   */
  public function getObjects() {
    return $this->objects;
  }

  /**
   * @return ParsedClass[]
   */
  public function getClasses() {
    return array_filter(
      $this->objects,
      function ($class) {
        return $class instanceof ParsedClass;
      }
    );
  }

  /**
   * @return ParsedInterface[]
   */
  public function getInterfaces() {
    return array_filter(
      $this->objects,
      function ($class) {
        return $class instanceof ParsedInterface;
      }
    );
  }

  public function enterNode(Node $node) {
    if (
      $node instanceof Namespace_
      &&
      NULL !== $node->name # We're not concerned about the global namespace
    ) {
      $this->currentNamespace = join('\\', $node->name->parts);
    } else if ($node instanceof Use_ && $node->type === Use_::TYPE_NORMAL) {
      $this->currentUseStatements[] = $node;
    } else if ($node instanceof PhpParserClass || $node instanceof PhpParserInterface) {
      /** @var ParsedObject|NULL $object */
      $object = NULL;
      if ($node instanceof PhpParserClass) {
        $object = new ParsedClass(
          $this->currentNamespace,
          $this->currentUseStatements,
          $node,
          $this->currentFile
        );
      } else if ($node instanceof PhpParserInterface) {
        $object = new ParsedInterface(
          $this->currentNamespace,
          $this->currentUseStatements,
          $node,
          $this->currentFile
        );
      }
      if (NULL !== $object) {
        try {
          $this->addObject($object);
        } catch (ObjectAlreadyExistsException $e) {
          $existingObject = $this->getObjectByFqn($object->getName());
          $msg =
            'Multiple declarations of the same type are not supported. Symbol ' .
            $object->getName() . ' from ' .
            $this->currentFile->getSplFile()->getRealPath() .
            ':' . $node->getLine();
          if ($existingObject instanceof ParsedObject) {
            $msg .= ' already found in ' .
              $existingObject->getFile()->getSplFile()->getRealPath() . ':' .
              $existingObject->getNode()->getLine() . ' ; only the first ' .
              'encounter is used';
          } else if ($existingObject instanceof ReflectedObject) {
            $msg .= ' clashes with internal ' .
              strtolower($existingObject->getKind()) . ' ' .
              $existingObject->getName();
          } else {
            throw new \RuntimeException('Unknown existing object '
              . $existingObject->getName());
          }
          $this->project->getLogger()->warning($msg);
        }
      }
    }

  }

  /**
   * @param GenericObject $obj
   * @return $this
   */
  public function addObject(GenericObject $obj) {
    $fqn = $obj->getName();
    if (isset($this->objects[$fqn])) {
      throw new ObjectAlreadyExistsException($obj);
    }
    $this->objects[$fqn] = $obj;
    return $this;
  }

  public function beforeTraverse(array $nodes) {
    $this->resetVisitorData();
  }

  public function resetVisitorData() {
    $this->currentNamespace = '';
    $this->currentUseStatements = [];
  }

  public function leaveNode(Node $node) {
  }

  public function afterTraverse(array $nodes) {
  }

  /**
   * @return Project
   */
  public function getProject() {
    return $this->project;
  }

  /**
   * @param Project $project
   * @return $this
   */
  public function setProject($project) {
    $this->project = $project;
    return $this;
  }
}

class ObjectTypeMissmatchException extends \RuntimeException {

  /** @var ParsedObject */
  private $current;
  /** @var ParsedObject */
  private $related;
  /** @var string */
  private $expected;

  /**
   * @param GenericObject $current
   * @param GenericObject $related
   * @param string $expectedKind
   */
  public function __construct(GenericObject $current, GenericObject $related, $expectedKind) {
    $this->current = $current;
    $this->related = $related;
    $this->expected = $expectedKind;

    $msg = $current->getKind() . ' ' . $current->getName();
    if ($current instanceof ParsedObject) {
      $msg .= ' found in ' .
        $current->getFile()->getSplFile()->getRealPath() . ':' .
        $current->getNode()->getLine();
    }
    $msg .= ' expected relation to ' . $related->getKind() . ' ' . $related->getName();
    if ($related instanceof ParsedObject) {
      $msg .= ' found in ' .
        $related->getFile()->getSplFile()->getRealPath() . ':' .
        $related->getNode()->getLine();
    }
    $msg .= ' to be of kind ' . $expectedKind . ' but is ' . $related->getKind()
      . ' instead';

    parent::__construct($msg);
  }
}

class ObjectAlreadyExistsException extends \RuntimeException {

  /** @var GenericObject */
  private $object;

  public function __construct(GenericObject $object) {
    $this->object = $object;
    parent::__construct(
      'Object with fqn ' . $object->getName() . ' already exists');
  }

  /**
   * @return GenericObject
   */
  public function getObject() {
    return $this->object;
  }
}

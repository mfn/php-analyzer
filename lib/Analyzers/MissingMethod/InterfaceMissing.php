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
namespace Mfn\PHP\Analyzer\Analyzers\MissingMethod;

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\Helper;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\ObjectGraph;
use Mfn\PHP\Analyzer\Analyzers\Tools\Class_;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedClass;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedInterface;
use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedMethod;
use Mfn\PHP\Analyzer\Project;
use Mfn\Util\Map\SimpleOrderedValidatingMap as Map;
use Mfn\Util\Map\SimpleOrderedValidatingMapException as MapException;

/**
 * Find un-implemented interface methods
 *
 * If you define an interface method and forget to implement it, the PHP linter
 * can only warn you if both are in the same file.
 *
 * This analyzer, based on the *ObjectGraph Analyzer*, finds all implementors
 * and other interfaces extending this one across your project.
 */
class InterfaceMissing extends Analyzer {

  /** @var ObjectGraph */
  private $objectGraph = NULL;

  public function __construct(ObjectGraph $objectGraph) {
    $this->objectGraph = $objectGraph;
    $this->helper = new Helper($objectGraph);
  }

  /**
   * @param Project $project
   * @return \string[]
   */
  public function analyze(Project $project) {
    $graph = $this->objectGraph;
    /** @var ParsedClass[] $classesMissingMethod */
    $classesMissingMethods = new Map(
      function ($key) {
        if ($key instanceof ParsedClass) {
          return;
        }
        throw new MapException('Only keys of type Class_ are accepted');
      },
      function ($value) {
        if (is_array($value)) {
          return;
        }
        throw new MapException('Only values of type array are accepted');
      }
    );
    # scan all objects (we're actually only interested in interfaces)
    foreach ($graph->getObjects() as $object) {
      if ($object instanceof ParsedInterface) {
        foreach ($object->getMethods() as $method) {
          # now find all classes and class from interfaces extending this
          # interface
          $classesMissingMethod =
            $this->findSubtypeUntilMethodMatchesRecursive(
              $method,
              $this->helper->findImplements($object)
            );
          # in case we found ones, store them for reporting later
          # note: we may find other methods in the same class later too
          foreach ($classesMissingMethod as $classMissingMethod) {
            $methods = [];
            if ($classesMissingMethods->exists($classMissingMethod)) {
              $methods = $classesMissingMethods->get($classMissingMethod);
            }
            $methods[] = $method;
            $classesMissingMethods->set($classMissingMethod, $methods);
          }
        }
      }
    }
    /** @var ParsedClass $class */
    foreach ($classesMissingMethods->keys() as $class) {
      $project->addReport(new InterfaceMissingReport(
          $class,
          $classesMissingMethods->get($class)
        )
      );
    }
  }

  /**
   * Recursively searches "down" the object graph to find classes, or classes
   * extended interfaces implementing this interface, which should implement
   * this method.
   *
   * Abstract classes are, like interfaces, "skipped" and their children are
   * scanned.
   *
   * @param ParsedMethod $interfaceMethod
   * @param ParsedInterface[]|ParsedClass[] $implementors
   * @return ParsedClass[]
   */
  private function findSubtypeUntilMethodMatchesRecursive(
    ParsedMethod $interfaceMethod,
    $implementors) {
    $classesMissingMethod = [];
    foreach ($implementors as $object) {

      # interfaces only delegate down to their implementors
      if ($object instanceof ParsedInterface) {
        $classesMissingMethod = array_merge(
          $classesMissingMethod,
          $this->findSubtypeUntilMethodMatchesRecursive(
            $interfaceMethod,
            $this->helper->findImplements($object)
          )
        );
        continue;
      } else if ($object instanceof ParsedClass) {

        $methodName = $interfaceMethod->getNormalizedName();

        # check if any parent implements the interface method
        if (
          Helper::classImplements($object->getParent(),
            $interfaceMethod->getInterface())
          ||
          self::classHasParentMethod($object->getParent(), $methodName)
        ) {
          continue;
        }

        $foundMethod = false;
        foreach ($object->getMethods() as $method) {
          if ($methodName === $method->getNormalizedName()) {
            $foundMethod = true;
            break;
          }
        }

        # abstract classes must not implement it ...
        if ($object->getClass()->isAbstract()) {
          if (!$foundMethod) {
            # ... but we search further down the inheritance tree
            $classesMissingMethod = array_merge(
              $classesMissingMethod,
              $this->findSubtypeUntilMethodMatchesRecursive(
                $interfaceMethod,
                $this->helper->findExtends($object)
              )
            );
          }
          continue;
        }

        if (!$foundMethod) {
          $classesMissingMethod[] = $object;
        }
      }
    }
    return $classesMissingMethod;
  }

  /**
   * @param Class_ $class
   * @param string $methodName
   * @return bool
   */
  private function classHasParentMethod(Class_ $class = NULL, $methodName) {
    if (NULL === $class) {
      return false;
    }
    foreach ($class->getMethods() as $method) {
      $classMethodName = $method->getNormalizedName();
      if ($classMethodName === $methodName) {
        return true;
      }
    }
    return self::classHasParentMethod($class->getParent(), $methodName);
  }

  /**
   * @return string
   */
  public function getName() {
    return 'InterfaceMissing';
  }
}

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

use Mfn\PHP\Analyzer\Analyzers\Analyzer;
use Mfn\PHP\Analyzer\Analyzers\Tools\ReflectedObject;
use Mfn\PHP\Analyzer\Project;

/**
 * Adds PHPs internal classes to the ObjectGraph so those and their methods
 * are resolvable too.
 *
 * Note: the actual classes/interfaces added depend on which are available
 * PHPs runtime when running this analyzer.
 */
class ReflectInternals extends Analyzer {

  /** @var ObjectGraph */
  private $graph;

  public function __construct(ObjectGraph $graph) {
    $this->graph = $graph;
  }

  /**
   * @return string
   */
  public function getName() {
    return 'ObjectGraphReflectInternals';
  }

  /**
   * @param Project $project
   */
  public function analyze(Project $project) {
    # Necessary, otherwise the graph doesn't know of the project because only
    # within it's own analyzer lifecycle would it receive it
    $this->graph->setProject($project);
    $numClasses = 0;
    $numInterfaces = 0;
    foreach ([get_declared_classes(), get_declared_interfaces()] as $set) {
      foreach ($set as $name) {
        $reflector = new \ReflectionClass($name);
        if ($reflector->isUserDefined()) {
          continue;
        }
        $object = ReflectedObject::createFromReflectionClass($reflector);
        $this->graph->addObject($object);
        if ($reflector->isInterface()) {
          $numInterfaces++;
        } else if (!$reflector->isTrait()) {
          $numClasses++;
        }
      }
    }
    $project->getLogger()->info(
      sprintf('Found %d classes and %d interfaces', $numClasses, $numInterfaces)
    );
    $this->graph->resolveGraph();
  }
}

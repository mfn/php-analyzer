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
namespace Mfn\PHP\Analyzer\Analyzers;

use Mfn\PHP\Analyzer\Project;

abstract class Analyzer {

  /**
   * How many lines of source context before/after
   * @var int
   */
  protected $sourceContext = 2;
  /**
   * One of the Severity:: constants
   * @var int
   */
  private $severity = Severity::ERROR;

  /**
   * @return int
   */
  public function getSourceContext() {
    return $this->sourceContext;
  }

  /**
   * @param int $sourceContext
   * @return $this
   */
  public function setSourceContext($sourceContext) {
    $this->sourceContext = $sourceContext;
    return $this;
  }

  /**
   * Returns the current severity
   *
   * @return integer
   */
  public function getSeverity() {
    return $this->severity;
  }

  /**
   * @param int $severity
   * @return $this
   */
  public function setSeverity($severity) {
    $this->severity = $severity;
    return $this;
  }

  /**
   * @return string
   */
  abstract public function getName();

  /**
   * @param Project $project
   */
  abstract public function analyze(Project $project);
}

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
namespace Mfn\PHP\Analyzer\Report;

/**
 * A report always returns a string; subclass it to attach more information.
 */
abstract class Report {

  /** @var NULL|SourceFragment */
  protected $sourceFragment = NULL;

  /**
   * @return SourceFragment|NULL
   */
  public function getSourceFragment() {
    return $this->sourceFragment;
  }

  /**
   * @param SourceFragment $sourceFragment
   * @return $this
   */
  public function setSourceFragment(SourceFragment $sourceFragment) {
    $this->sourceFragment = $sourceFragment;
    return $this;
  }

  /**
   * Return an array with serialize discrete values
   *
   * If you subclass Report and want to add your own data to the serializer,
   * it's recommended to create your data in an array and union it with the
   * parent to chain in the parents definitions but don't override your own.
   *
   * Example:
   *    public function toArray() {
   *      return [
   *        'more' => 'data',
   *      ] + parent::toArray();
   *    }
   *
   * @return array
   */
  public function toArray() {
    $data = [
      'kind'   => get_class($this),
      'report' => $this->report(),
    ];
    if (NULL !== $this->sourceFragment) {
      $data['sourceFragment'] = $this->sourceFragment->toArray();
    }
    return $data;
  }

  /**
   * @return string
   */
  abstract public function report();
}

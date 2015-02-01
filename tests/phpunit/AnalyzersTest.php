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
namespace Mfn\PHP\Analyzer;

use Mfn\PHP\Analyzer\Analyzers\CakePHP2\QueryConditionVariables;
use Mfn\PHP\Analyzer\Analyzers\DynamicClassInstantiation;
use Mfn\PHP\Analyzer\Analyzers\InterfaceMethodAbstract;
use Mfn\PHP\Analyzer\Analyzers\MethodCompatibility\MethodCompatibility;
use Mfn\PHP\Analyzer\Analyzers\MissingMethod\AbstractMissing;
use Mfn\PHP\Analyzer\Analyzers\MissingMethod\InterfaceMissing;
use Mfn\PHP\Analyzer\Analyzers\NameResolver;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\ObjectGraph;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\ReflectInternals;
use Mfn\PHP\Analyzer\Analyzers\Parser;
use Mfn\PHP\Analyzer\Logger\Null;
use Mfn\PHP\Analyzer\Util\Util;
use PhpParser\Lexer;

class AnalyzerTest extends \PHPUnit_Framework_TestCase {
  /** @var Project */
  private $project;
  /** @var ObjectGraph */
  private $graph;

  public function setUp() {
    $this->project = new Project(Null::getInstance());
    $this->project->addAnalyzer(new Parser(new \PhpParser\Parser(new Lexer())));
    $this->project->addAnalyzer(new NameResolver());
    $this->graph = new ObjectGraph();
  }

  public function testAbstractMissingAnalyzer() {
    $this->project->addSplFileInfo(
      new \SplFileInfo(self::getAnalyzerFilename('001_abstract_method_missing'))
    );
    $this->project->addAnalyzer($this->graph);
    $this->project->addAnalyzer(new AbstractMissing($this->graph));
    $this->project->analyze();
    $reports = $this->project->getAnalyzerReports();
    $this->assertSame(1, count($reports));
    $this->assertSame(
      'Class Mfn\PHP\Analyzer\Tests\AbstractMethodMissing\b misses the following abstract method: Mfn\PHP\Analyzer\Tests\AbstractMethodMissing\a::b()',
      $reports[0]->getTimestampedReport()->getReport()->report()
    );
  }

  static private function getAnalyzerFilename($name) {
    return self::getAnalyzerTestsDir() . $name . '.phptest';
  }

  static private function getAnalyzerTestsDir() {
    return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
    . 'analyzer' . DIRECTORY_SEPARATOR;

  }

  public function testInterfaceMissingAnalyzerNotMissing() {
    $this->project->addSplFileInfo(
      new \SplFileInfo(self::getAnalyzerFilename('007_interface_method_not_missing'))
    );
    $this->project->addAnalyzer($this->graph);
    $this->project->addAnalyzer(new InterfaceMissing($this->graph));
    $this->project->analyze();
    $reports = $this->project->getAnalyzerReports();
    $this->assertSame(0, count($reports));
  }

  public function testInterfaceMissingAnalyzer() {
    $this->project->addSplFileInfo(
      new \SplFileInfo(self::getAnalyzerFilename('006_interface_method_missing'))
    );
    $this->project->addAnalyzer($this->graph);
    $this->project->addAnalyzer(new InterfaceMissing($this->graph));
    $this->project->analyze();
    $reports = $this->project->getAnalyzerReports();
    $this->assertSame(1, count($reports));
    $this->assertSame(
      'Class Mfn\PHP\Analyzer\Tests\InterfaceMethodMissing\d misses the following interface method: Mfn\PHP\Analyzer\Tests\InterfaceMethodMissing\a::c()',
      $reports[0]->getTimestampedReport()->getReport()->report()
    );
  }

  public function testMethodCompatibilityAnalyzer() {
    $this->project->addSplFileInfo(
      new \SplFileInfo(self::getAnalyzerFilename('008_method_declaration_compability'))
    );
    $this->project->addAnalyzer($this->graph);
    $this->project->addAnalyzer(new MethodCompatibility($this->graph));
    $this->project->analyze();
    $reports = $this->project->getAnalyzerReports();
    $this->assertSame(1, count($reports));
    $this->assertSame(
      'Declaration of Mfn\PHP\Analyzer\Tests\MethodDeclarationCompatibility\b::c($a, $a) must be compatible with Mfn\PHP\Analyzer\Tests\MethodDeclarationCompatibility\a::c(array $a = 1)',
      $reports[0]->getTimestampedReport()->getReport()->report()
    );
  }

  public function testInterfaceMethodAbstract() {
    $this->project->addSplFileInfo(
      new \SplFileInfo(self::getAnalyzerFilename('004_interface_method_abstract'))
    );
    $this->project->addAnalyzer($this->graph);
    $this->project->addAnalyzer(new InterfaceMethodAbstract($this->graph));
    $this->project->analyze();
    $reports = $this->project->getAnalyzerReports();
    $this->assertSame(1, count($reports));
    $this->assertSame(
      'Access type for interface method Mfn\PHP\Analyzer\Tests\InterfaceMethodAbstract\Foo::bar() must be ommmited in /Users/mfischer/src/mfn-php-analyzer/tests/analyzer/004_interface_method_abstract.phptest:27',
      $reports[0]->getTimestampedReport()->getReport()->report()
    );
  }

  public function testDynamicClassInstantiation() {
    $project = new Project(Null::getInstance());
    $project->addSplFileInfo(
      new \SplFileInfo(self::getAnalyzerFilename('003_dynamic_class_instantiation'))
    );
    $project->addAnalyzer(new Parser(new \PhpParser\Parser(new Lexer())));
    $project->addAnalyzer(new DynamicClassInstantiation());
    $project->analyze();
    $reports = $project->getAnalyzerReports();
    $this->assertSame(1, count($reports));
    $this->assertSame(
      'Dynamic class instantiation with variable $foo in 003_dynamic_class_instantiation.phptest:26',
      $reports[0]->getTimestampedReport()->getReport()->report()
    );
  }

  public function testCakePHP2ConditionVariables() {
    $project = new Project(Null::getInstance());
    $project->addSplFileInfo(
      new \SplFileInfo(self::getAnalyzerFilename('002_cakephp2_condition_variables'))
    );
    $project->addAnalyzer(new Parser(new \PhpParser\Parser(new Lexer())));
    $project->addAnalyzer(new QueryConditionVariables());
    $project->analyze();
    $reports = $project->getAnalyzerReports();
    $this->assertSame(3, count($reports));
    $this->assertSame(
      'Variable used in constructing raw SQL, is it escaped?',
      $reports[0]->getTimestampedReport()->getReport()->report()
    );
    $this->assertSame(
      29,
      $reports[0]->getTimestampedReport()->getReport()->getSourceFragment()->getLineSegment()->getHighlightLine()
    );
    $this->assertSame(
      30,
      $reports[1]->getTimestampedReport()->getReport()->getSourceFragment()->getLineSegment()->getHighlightLine()
    );
    $this->assertSame(
      31,
      $reports[2]->getTimestampedReport()->getReport()->getSourceFragment()->getLineSegment()->getHighlightLine()
    );
  }

  /**
   * This currently is expected to fail but should not
   */
  public function testInterfaceMethodImplementedInternal() {
    $this->project->addSplFileInfo(
      new \SplFileInfo(self::getAnalyzerFilename('005_interface_method_implemented_internal'))
    );
    $this->project->addAnalyzer(new ReflectInternals($this->graph));
    $this->project->addAnalyzer(new InterfaceMissing($this->graph));
    $this->project->analyze();
    $reports = $this->project->getAnalyzerReports();
    $this->assertSame(0, count($reports));
  }

  /**
   * Although this test seems redundant I use it to ensure that as far as it's
   * possible the analyzers do not negatively affect each other.
   */
  public function testAllAnalyzers() {
    $project = new Project(Null::getInstance());
    foreach (Util::scanDir(self::getAnalyzerTestsDir(), '/\.phptest$/') as $file) {
      $project->addSplFileInfo(new \SplFileInfo($file));
    }
    $project->addAnalyzers(Project::getDefaultConfig());
    $project->analyze();
    $reports = $project->getAnalyzerReports();
    $this->assertSame(8, count($reports));
    $this->assertSame(
      'Class Mfn\PHP\Analyzer\Tests\AbstractMethodMissing\b misses the following abstract method: Mfn\PHP\Analyzer\Tests\AbstractMethodMissing\a::b()',
      $reports[0]->getTimestampedReport()->getReport()->report()
    );
    $this->assertSame(
      'Class Mfn\PHP\Analyzer\Tests\InterfaceMethodMissing\d misses the following interface method: Mfn\PHP\Analyzer\Tests\InterfaceMethodMissing\a::c()',
      $reports[1]->getTimestampedReport()->getReport()->report()
    );
    $this->assertSame(
      'Declaration of Mfn\PHP\Analyzer\Tests\MethodDeclarationCompatibility\b::c($a, $a) must be compatible with Mfn\PHP\Analyzer\Tests\MethodDeclarationCompatibility\a::c(array $a = 1)',
      $reports[2]->getTimestampedReport()->getReport()->report()
    );
    $this->assertSame(
      'Access type for interface method Mfn\PHP\Analyzer\Tests\InterfaceMethodAbstract\Foo::bar() must be ommmited in /Users/mfischer/src/mfn-php-analyzer/tests/analyzer/004_interface_method_abstract.phptest:27',
      $reports[3]->getTimestampedReport()->getReport()->report()
    );
    $this->assertSame(
      'Dynamic class instantiation with variable $foo in 003_dynamic_class_instantiation.phptest:26',
      $reports[4]->getTimestampedReport()->getReport()->report()
    );
    $this->assertSame(
      'Variable used in constructing raw SQL, is it escaped?',
      $reports[5]->getTimestampedReport()->getReport()->report()
    );
    $this->assertSame(
      29,
      $reports[5]->getTimestampedReport()->getReport()->getSourceFragment()->getLineSegment()->getHighlightLine()
    );
    $this->assertSame(
      30,
      $reports[6]->getTimestampedReport()->getReport()->getSourceFragment()->getLineSegment()->getHighlightLine()
    );
    $this->assertSame(
      31,
      $reports[7]->getTimestampedReport()->getReport()->getSourceFragment()->getLineSegment()->getHighlightLine()
    );
  }
}

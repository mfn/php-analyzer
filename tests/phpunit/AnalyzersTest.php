<?php
namespace Mfn\PHP\Analyzer;

use Mfn\PHP\Analyzer\Analyzers\CakePHP2\QueryConditionVariables;
use Mfn\PHP\Analyzer\Analyzers\DynamicClassInstantiation;
use Mfn\PHP\Analyzer\Analyzers\ExceptionEmptyCatch\ExceptionEmptyCatch;
use Mfn\PHP\Analyzer\Analyzers\InterfaceMethodAbstract;
use Mfn\PHP\Analyzer\Analyzers\MethodCompatibility\MethodCompatibility;
use Mfn\PHP\Analyzer\Analyzers\MissingMethod\AbstractMissing;
use Mfn\PHP\Analyzer\Analyzers\MissingMethod\InterfaceMissing;
use Mfn\PHP\Analyzer\Analyzers\NameResolver;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\ObjectGraph;
use Mfn\PHP\Analyzer\Analyzers\ObjectGraph\ReflectInternals;
use Mfn\PHP\Analyzer\Analyzers\Parser;
use Mfn\PHP\Analyzer\Logger\NullLogger;
use Mfn\PHP\Analyzer\Util\Util;
use PhpParser\Lexer;
use PHPUnit\Framework\TestCase;

class AnalyzersTest extends TestCase
{
    /** @var Project */
    private $project;
    /** @var ObjectGraph */
    private $graph;

    public function setUp()
    {
        $this->project = new Project(NullLogger::getInstance());
        $this->project->addAnalyzer(new Parser(new \PhpParser\Parser(new Lexer())));
        $this->project->addAnalyzer(new NameResolver());
        $this->graph = new ObjectGraph();
    }

    public function testAbstractMissingAnalyzer()
    {
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

    static private function getAnalyzerFilename($name)
    {
        return self::getAnalyzerTestsDir() . $name . '.phptest';
    }

    static private function getAnalyzerTestsDir()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
        . 'analyzer' . DIRECTORY_SEPARATOR;

    }

    public function testInterfaceMissingAnalyzerNotMissing()
    {
        $this->project->addSplFileInfo(
            new \SplFileInfo(self::getAnalyzerFilename('007_interface_method_not_missing'))
        );
        $this->project->addAnalyzer($this->graph);
        $this->project->addAnalyzer(new InterfaceMissing($this->graph));
        $this->project->analyze();
        $reports = $this->project->getAnalyzerReports();
        $this->assertSame(0, count($reports));
    }

    public function testInterfaceMissingAnalyzer()
    {
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

    public function testMethodCompatibilityAnalyzer()
    {
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

    public function testInterfaceMethodAbstract()
    {
        $this->project->addSplFileInfo(
            new \SplFileInfo(self::getAnalyzerFilename('004_interface_method_abstract'))
        );
        $this->project->addAnalyzer($this->graph);
        $this->project->addAnalyzer(new InterfaceMethodAbstract($this->graph));
        $this->project->analyze();
        $reports = $this->project->getAnalyzerReports();
        $this->assertSame(1, count($reports));
        $this->assertSame(4, $reports[0]->getSourceFragment()->getLineSegment()->getHighlightLine());
    }

    public function testDynamicClassInstantiation()
    {
        $project = new Project(NullLogger::getInstance());
        $project->addSplFileInfo(
            new \SplFileInfo(self::getAnalyzerFilename('003_dynamic_class_instantiation'))
        );
        $project->addAnalyzer(new Parser(new \PhpParser\Parser(new Lexer())));
        $project->addAnalyzer(new DynamicClassInstantiation());
        $project->analyze();
        $reports = $project->getAnalyzerReports();
        $this->assertSame(1, count($reports));
        $this->assertSame(
            'Dynamic class instantiation with variable $foo in 003_dynamic_class_instantiation.phptest:3',
            $reports[0]->getTimestampedReport()->getReport()->report()
        );
    }

    public function testCakePHP2ConditionVariables()
    {
        $project = new Project(NullLogger::getInstance());
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
            6,
            $reports[0]->getTimestampedReport()->getReport()->getSourceFragment()->getLineSegment()->getHighlightLine()
        );
        $this->assertSame(
            7,
            $reports[1]->getTimestampedReport()->getReport()->getSourceFragment()->getLineSegment()->getHighlightLine()
        );
        $this->assertSame(
            8,
            $reports[2]->getTimestampedReport()->getReport()->getSourceFragment()->getLineSegment()->getHighlightLine()
        );
    }

    /**
     * This currently is expected to fail but should not
     */
    public function testInterfaceMethodImplementedInternal()
    {
        $this->project->addSplFileInfo(
            new \SplFileInfo(self::getAnalyzerFilename('005_interface_method_implemented_internal'))
        );
        $this->project->addAnalyzer(new ReflectInternals($this->graph));
        $this->project->addAnalyzer(new InterfaceMissing($this->graph));
        $this->project->analyze();
        $reports = $this->project->getAnalyzerReports();
        $this->assertSame(0, count($reports));
    }

    public function testExceptionEmptyCatchBlockAnalyzer()
    {
        $this->project->addSplFileInfo(
            new \SplFileInfo(self::getAnalyzerFilename('009_empty_catch_block'))
        );
        $this->project->addAnalyzer(new ExceptionEmptyCatch());
        $this->project->analyze();
        $reports = $this->project->getAnalyzerReports();
        $this->assertSame(2, count($reports));
        # Line counting starts with 0
        $this->assertSame(3, $reports[0]->getSourceFragment()->getLineSegment()->getHighlightLine());
        $this->assertSame(8, $reports[1]->getSourceFragment()->getLineSegment()->getHighlightLine());
    }


    /**
     * Although this test seems redundant I use it to ensure that as far as it's
     * possible the analyzers do not negatively affect each other.
     */
    public function testAllAnalyzers()
    {
        $project = new Project(NullLogger::getInstance());
        foreach (Util::scanDir(self::getAnalyzerTestsDir(), '/\.phptest$/') as $file) {
            $project->addSplFileInfo(new \SplFileInfo($file));
        }
        $project->addAnalyzers(Project::getDefaultConfig());
        $project->analyze();
        $reports = $project->getAnalyzerReports();
        $this->assertSame(10, count($reports));
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
        # Empty exception catch block reports
        $this->assertSame(4, $reports[3]->getSourceFragment()->getLineSegment()->getHighlightLine());
        $this->assertSame(
            'Dynamic class instantiation with variable $foo in 003_dynamic_class_instantiation.phptest:3',
            $reports[4]->getTimestampedReport()->getReport()->report()
        );
        $this->assertSame(
            'Variable used in constructing raw SQL, is it escaped?',
            $reports[5]->getTimestampedReport()->getReport()->report()
        );
        $this->assertSame(
            6,
            $reports[5]->getTimestampedReport()->getReport()->getSourceFragment()->getLineSegment()->getHighlightLine()
        );
        $this->assertSame(
            7,
            $reports[6]->getTimestampedReport()->getReport()->getSourceFragment()->getLineSegment()->getHighlightLine()
        );
        $this->assertSame(
            8,
            $reports[7]->getTimestampedReport()->getReport()->getSourceFragment()->getLineSegment()->getHighlightLine()
        );
        # Empty exception catch block reports
        $this->assertSame(3, $reports[8]->getSourceFragment()->getLineSegment()->getHighlightLine());
        $this->assertSame(8, $reports[9]->getSourceFragment()->getLineSegment()->getHighlightLine());
    }
}

<?php
/*
 * Default configuration; enable all analyzers
 */
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
use PhpParser\Lexer;

$objectGraph = new ObjectGraph();

/** @return \Mfn\PHP\Analyzer\Analyzers\Analyzer[] */
return [
    new Parser(new \PhpParser\Parser(new Lexer())),
    new NameResolver(),
    $objectGraph,
    new ReflectInternals($objectGraph),
    new AbstractMissing($objectGraph),
    new InterfaceMissing($objectGraph),
    new MethodCompatibility($objectGraph),
    new InterfaceMethodAbstract($objectGraph),
    new DynamicClassInstantiation(),
    new QueryConditionVariables(),
    new ExceptionEmptyCatch(),
];

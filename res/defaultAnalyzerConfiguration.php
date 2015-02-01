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

/*
 * Default configuration; enable all analyzers
 */
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
];

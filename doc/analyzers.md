# Built-in / available Analyzers

#### Class [Mfn\PHP\Analyzer\Analyzers\CakePHP2\QueryConditionVariables](/lib/Analyzers/CakePHP2/QueryConditionVariables.php)

Find CakePHP2 query `conditions` arrays which use variable interpolation.

Emits a warning for every occurrence where `conditions` key (for querying
models) is found and uses variable interpolation in its statements.

This helps checking those parts and ensure these variables, which are one
source of SQL injections, are properly escaped.

Limitations:
- cannot detect whether the variable is properly escaped, thus
  the warnings are always generated which limits its usefulness.
- does not support nested `conditions`

#### Class [Mfn\PHP\Analyzer\Analyzers\DynamicClassInstantiation](/lib/Analyzers/DynamicClassInstantiation.php)

Report all occurrences of dynamic class invocation, e.g. `new $foo`.

Those *can be* a source of bad design and hint for refactoring; this analyzer
reports all source code fragments contain dynamic class instantiation.

#### Class [Mfn\PHP\Analyzer\Analyzers\InterfaceMethodAbstract](/lib/Analyzers/InterfaceMethodAbstract.php)

Finds wrong interface method access types.

Currently it reports `abstract function` when appearing as part of an
interface. This analyzer isn't really that useful because this error
also covered by the PHP linter itself.

#### Class [Mfn\PHP\Analyzer\Analyzers\MethodCompatibility\MethodCompatibility](/lib/Analyzers/MethodCompatibility/MethodCompatibility.php)

Find all override methods whose signature has changed.

Currently the PHP internal cannot detect this on static analysis, only
during runtime are these errors exposed.

#### Class [Mfn\PHP\Analyzer\Analyzers\MissingMethod\AbstractMissing](/lib/Analyzers/MissingMethod/AbstractMissing.php)

Find un-implemented abstract methods

If you define an abstract method and forget to implement it, the PHP linter
can only warn you if both are in the same file.

This analyzer, based on the *ObjectGraph Analyzer*, finds all sub-classes
missing abstract method implementations across your project.

#### Class [Mfn\PHP\Analyzer\Analyzers\MissingMethod\InterfaceMissing](/lib/Analyzers/MissingMethod/InterfaceMissing.php)

Find un-implemented interface methods

If you define an interface method and forget to implement it, the PHP linter
can only warn you if both are in the same file.

This analyzer, based on the *ObjectGraph Analyzer*, finds all implementors
and other interfaces extending this one across your project.

#### Class [Mfn\PHP\Analyzer\Analyzers\NameResolver](/lib/Analyzers/NameResolver.php)

Runs the nikic/PhpParser `NameResolver`

This is usually run after the `Parser` analyzer.

The purpose is to have the PhpParser NameResolver run which will throw
exception on duplicate defined names; this ensures further Analyzers
have at least these things already covered.

#### Class [Mfn\PHP\Analyzer\Analyzers\ObjectGraph\ObjectGraph](/lib/Analyzers/ObjectGraph/ObjectGraph.php)

Builds a graph of all the object (classes, interfaces) and "connects" them
together, allowing the object tree to be further analysed.

It also includes full qualified name resolution for relevant names; this
may be obsolete due using PhpParser\NodeVisitor\NameResolver but it's using
it's own implementation for now.

You will often encounter the term "fqn" which stands for "full qualified name".
A full qualified name includes the namespace, i.e. "My\Name\Spaced\Class". A
fqn **does not** include the leading backslash `\`.


Various methods exist to access the objects (once the tree is built):
- `getObjectByFqn()`
- `getClassByFqn()`
- `getInterfaceByFqn()`
- ... many more

The ObjectGraph uses `nikic/PhpParser` to collect classes/interfaces and wraps
those in own `Class_` or `Interface_` objects which provide further methods
to navigate around the object, e.g. they project methods to access the
parent class with `Class_::getInterfaces`, etc.

#### Class [Mfn\PHP\Analyzer\Analyzers\ObjectGraph\ReflectInternals](/lib/Analyzers/ObjectGraph/ReflectInternals.php)

Adds PHPs internal classes to the ObjectGraph so those and their methods
are resolvable too.

Note: the actual classes/interfaces added depend on which are available
PHPs runtime when running this analyzer.

#### Class [Mfn\PHP\Analyzer\Analyzers\Parser](/lib/Analyzers/Parser.php)

Parses the PHP source files into an AST and add them to the project.

This should usually be your first analyzer.

It expects all files to be scanned to be added to the `Project` already
(`\SplFileInfo`) and parses them using the nikic/PhpParser library.


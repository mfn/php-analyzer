# PHP-Analzyer

Homepage: https://github.com/mfn/php-analyzer

## Blurb

A [framework](doc/framework.md) for performing static PHP source analysis.
The modular concept hands analysis over to [Analyzers](doc/analyzers.md) which
report back any possible warnings/errors.

## Install
Via [composer](https://getcomposer.org):
```
composer require mfn/php-analyzer 0.0.1
```

## Usage

A command line tool is provided: `php_analyzer.php analyze <files or dirs>`

See the `--help` switch for more details.

# What analysis is performed?

All files are run through the [nikic/PHP-Parser](https://github.com/nikic/PHP-Parser)
and after that an internal graph of the classes/interfaces is built.

All analyzers available are run against the sources reports are generated.

The following analysis is currently performed:
- detection of missing method implementations of abstract classes
- detecting of missing method implementations of interfaces
- incompatibility of methods declared in interfaces
- methods defined abstract on an interface<br>
  Probably a bit pointless because the php linter detects this.
- warns when using dynamic class instantiation, i.e. `new $foo`

Learn more:
- [The architecture of the Framework itself](doc/framework.md)
- [Documentations of all Analyzers](doc/analyzers.md)
- [Default Analyzer configuration](res/defaultAnalyzerConfiguration.php)

## Graphviz

The internal [ObjectGraph](lib/Analyzers/ObjectGraph/ObjectGraph.php) lends
itself to generate a class relationship diagram for which a graphviz generator
exists. This will produce a `.dot` file which can be further used with the
[Graphviz](http://www.graphviz.org/) package to generate graphics from it:

`php_analyzer.php graphviz yoursource/ > myproject.dot`

See `--help` for more options.

To convert this to e.g. png the aforementioned graphviz package has to be
installed on your system. This includes the `dot` command which can be used to
generate a PNG file:

`dot -Tpng myproject.dot > myproject.png`

## Configuration

If you want to use a differnet set of analyzers or you've written your own and
want to use them, you can use the `--config <file>` option.

The file is a plain PHP file simply returning an array of analyzers you want to
run. See [res/defaultAnalyzerConfiguration.php](res/defaultAnalyzerConfiguration.php)
for an example.

## Phing integration

A task for Phing is also included; this is currently only tested with composer
which takes care of autoloading the namespace classes properly into Phing.

The phing tasks supports the fileset subnode.

```XML
<taskdef name="mfn-php-analyzer" classname="Mfn\PHP\Analyzer\PhingTask"/>
<target name="analyze">
  <mfn-php-analyzer
    haltonerror="true"
    >
    <fileset dir="lib" />
  </mfn-php-analyzer>
</target>
```

The following attributes are supported:
- `haltonerror` : boolean true/false ; defaults to true
- `configfile`: a plain PHP file which is expected to return an array of
`Analyzer`; see `res/defaultAnalyzerConfiguration.php` for an example.
- `logfile` : write analysis result to this file<br>
If `logfile` is used, analyzer warnings and errors are not sent to Phing (but
builds are still aborted on errors, i.e. the first *error* is reported)
- `logFormat` : format of the logfile. Supported options
  - `plain` : a plain text format
  - `json` : JSON format, an array of all reports
  - `json-pretty` : as above, but using pretty printer

# TODOs / Ideas
- the analyzers depending on the graph have no logic whether they've visited a
  node already or not; thus visiting the same nodes/methods multiple times
- Use `namespacedName` property generated by `\PhpParser\NodeVisitor\NameResolver`
- Add support for traits

© Markus Fischer <markus@fischer.name>

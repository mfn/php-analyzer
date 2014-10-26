# Overview of the PHP Analyzer framework

## Geneal setup

(Note: for brevity namespaces are omitted)

Everything starts with a `Project` and you pass a logger to it's constructor:
```PHP
$project = new Project( new Stdout() );
```

Add all the files you want to analyze:
```PHP
$project->addSplFileInfo( \new SplFileInfo('source.php') );
```

Now add some analyzers; usually your first Analyzer should be the Parser to
actually parse the files; this analyzer adds the parsed files back to the
project for all the other analysers. The included
[Parser Analyzer](/lib/Analyzers/Parser.php) uses the excellent
[nikic/php-parser library](https://github.com/nikic/PHP-Parser) :
```PHP
$project->addAnalyzer(new Parser(new \PhpParser\Parser(new Lexer())));
```

Most of the included Analyzers depend on the graph of classes/interfaces and
their relation; the [ObjectGraph Analyzer](lib/Analyzers/ObjectGraph/ObjectGraph.php)
builds these, so usually you want to add it too:
```PHP
$project->addAnalyzer($objectGraph = new ObjectGraph());
```

And because other Analyzers use it, we store it in a variable and pass it on, e.g.:
```PHP
$project->addAnalyzer(new AbstractMissing($objectGraph));
```

## Anatomy of an Analyzer

An Analyzer has to implement `Analyzers\Analyzer` and uses the `Project` from the
`analyze()` method to report back problems encountered during analyzing:
```PHP
public function analyze(Project $project) {
  # analyse code ...
  foreach ($project->getFiles() as $file) {
    # error detected
    $project->addReport( new StringReport( 'Some error found' ) );
  }
}
```

The `Project` collects all those reports. After the all analyzers have been run,
these reports can be sent to the upstream application:
```PHP
foreach ($project->getAnalyzerReports() as $analyzerReport) {
  $report = $analyzerReport->getTimestampedReport()->getReport();
  echo $report->report(), PHP_EOL;
}
```

An analyzer is supposed to check the source for a specific unit of thought. Thus
the severity level is set on the analyzers themselves and not on individual
errors they report. This allows configure the severity of specific analysers
from the outside, depending on the importance for a given project.


Also see the list of [built in analyzers](analyzers.md).

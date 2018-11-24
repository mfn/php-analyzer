<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Logger;

use Symfony\Component\Console\Output\OutputInterface;

class SymfonyConsoleOutput extends ProjectLogger
{

    /** @var OutputInterface */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * The actual implementation to perform the logging action
     *
     * The level is always int; if you want a string representation use
     * levelToInt().
     *
     * If you want to interpolate the context, use interpolateContext()
     *
     * @param int $level
     * @param string $message
     * @param array $context
     * @return NULL
     */
    protected function realLog($level, $message, array $context = [])
    {
        $message = self::interpolateContext($message, $context);
        $this->output->writeln(
            sprintf(
                '[%s] %s',
                self::levelToString($level),
                $message
            ),
            OutputInterface::OUTPUT_RAW
        );
        return null;
    }
}

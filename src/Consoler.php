<?php

namespace Savks\Consoler;

use Illuminate\Console\OutputStyle;
use ReflectionObject;
use Savks\Consoler\Support\{
    Format,
    SpinnerProgress,
    TextProgress};
use Symfony\Component\Console\Output\{
    ConsoleOutput,
    OutputInterface
};

class Consoler
{
    /**
     * @var ConsoleOutput|OutputInterface
     */
    protected $output;

    /**
     * Consoler constructor.
     * @param OutputInterface|null $output
     */
    public function __construct(OutputInterface $output = null)
    {
        $this->output = $output ?: new ConsoleOutput();
    }

    /**
     * @param OutputStyle $outputStyle
     * @return static
     */
    public static function fromOutputStyle(OutputStyle $outputStyle): Consoler
    {
        $reflection = new ReflectionObject($outputStyle);

        $property = $reflection->getProperty('output');

        $property->setAccessible(true);

        return new static(
            $property->getValue($outputStyle)
        );
    }

    /**
     * @param int $max
     * @param bool $showImmediately
     * @return SpinnerProgress
     */
    public function createSpinnerProgress(
        int $max = 0,
        bool $showImmediately = true
    ): SpinnerProgress {
        return new SpinnerProgress($max, $showImmediately, $this->output);
    }

    /**
     * @param string $format
     * @param array $placeholdersData
     * @param int $max
     * @param bool $showImmediately
     * @return TextProgress
     */
    public function createTextProgress(
        string $format,
        array $placeholdersData = [],
        int $max = 0,
        bool $showImmediately = true
    ): TextProgress {
        return new TextProgress(
            $format,
            $placeholdersData,
            $max,
            $showImmediately,
            $this->output
        );
    }

    /**
     * @param string $text
     * @return Format
     */
    public function format(string $text): Format
    {
        return Format::for($text);
    }

    /**
     * @param string $format
     * @param mixed ...$values
     */
    public function formatWriteToOutput(string $format, ...$values): void
    {
        $this->output->write(
            sprintf($format, ...$values)
        );
    }

    /**
     * @param string $format
     * @param mixed ...$values
     */
    public function formatWritelnToOutput(string $format, ...$values): void
    {
        $this->output->writeln(
            sprintf($format, ...$values)
        );
    }

    /**
     * @param string $message
     */
    public function writeToOutput(string $message): void
    {
        $this->output->write($message);
    }

    /**
     * @param string $message
     */
    public function writelnToOutput(string $message): void
    {
        $this->output->writeln($message);
    }
}

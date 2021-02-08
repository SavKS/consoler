<?php

namespace Savks\Consoler\Support;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\{
    ConsoleOutput,
    OutputInterface
};

abstract class BaseInlineProgress
{
    /**
     * @var int|null
     */
    protected $max;

    /**
     * @var ProgressBar
     */
    protected $progressBar;

    /**
     * @var int
     */
    protected $step = 0;

    /**
     * @param int $max
     * @param OutputInterface|null $output
     */
    protected function initProgressBar(int $max = 0, OutputInterface $output = null): void
    {
        $output = $output ?: new ConsoleOutput();

        $this->progressBar = new ProgressBar(
            $output->section(),
            $max
        );

        $this->progressBar->setBarWidth(1);
    }

    /**
     * @param int $max
     * @return static
     */
    public function setMax(int $max)
    {
        $this->progressBar->setMaxSteps($max);

        return $this;
    }

    /**
     * @param string $format
     * @return static
     */
    public function setFormat(string $format)
    {
        $this->progressBar->setFormat($format);

        return $this;
    }

    /**
     * @param array $data
     * @param bool $refreshOutput
     * @return static
     */
    public function updatePlaceholders(array $data, bool $refreshOutput = true)
    {
        foreach ($data as $name => $value) {
            $this->progressBar->setMessage($value, $name);
        }

        if ($refreshOutput && $this->progressBar->getStartTime()) {
            $this->progressBar->display();
        }

        return $this;
    }

    /**
     * @return void
     */
    public function show(): void
    {
        $this->progressBar->start();
    }

    /**
     * @return void
     */
    public function finish(): void
    {
        $this->progressBar->finish();
    }
}

<?php

namespace Savks\Consoler\Support;

use Illuminate\Support\Str;
use Symfony\Component\Console\Output\OutputInterface;

class SpinnerProgress extends BaseInlineProgress
{
    /**
     * @var array
     */
    protected $theme;

    /**
     * @var array
     */
    protected $themeProgressCharsCount;

    /**
     * Spinner constructor.
     * @param int|null $max
     * @param bool $showImmediately
     * @param OutputInterface|null $output
     */
    public function __construct(int $max = 0, bool $showImmediately = true, OutputInterface $output = null)
    {
        $this->initProgressBar($max, $output);

        $this->setFormat('%spinner%');
        $this->setTheme(SpinnerThemes::DEFAULT);

        if ($showImmediately) {
            $this->progressBar->start();
        }
    }

    /**
     * @param string $format
     * @return SpinnerProgress
     */
    public function setFormat(string $format)
    {
        if (! Str::contains($format, '%spinner%')) {
            $format = "%spinner% {$format}";
        }

        $format = str_replace('%spinner%', '%bar%', $format);

        return parent::setFormat($format);
    }

    /**
     * @param string $text
     * @param string|null $format
     * @return $this
     */
    public function withMessage(string $text, string $format = null): self
    {
        $this->setFormat($format ?: '%spinner% %message%');

        $this->updatePlaceholders(['message' => $text]);

        return $this;
    }

    /**
     * @param array $config
     * @return SpinnerProgress
     */
    public function setTheme(array $config): SpinnerProgress
    {
        $this->progressBar->setRedrawFrequency(30);

        $this->theme = $config;

        $this->themeProgressCharsCount = count($this->theme['progress']);

        $this->progressBar->setProgressCharacter($this->theme['start']);
        $this->progressBar->setBarCharacter($this->theme['success']);

        return $this;
    }

    /**
     * @return void
     */
    public function advance(): void
    {
        ++$this->step;

        $this->progressBar->setProgressCharacter(
            $this->theme['progress'][$this->step % $this->themeProgressCharsCount]
        );

        $this->progressBar->advance();
    }

    /**
     * @return void
     */
    public function finish(): void
    {
        if ($this->progressBar->getProgress() === 0) {
            $this->advance();
        }

        parent::finish();
    }

    /**
     * @return void
     */
    public function success(): void
    {
        $this->finish();
    }

    /**
     * @return void
     */
    public function fail(): void
    {
        $this->progressBar->setBarCharacter($this->theme['fail']);

        $this->finish();
    }
}

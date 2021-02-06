<?php

namespace Savks\Consoler\Support;

use Symfony\Component\Console\Output\OutputInterface;

class TextProgress extends BaseInlineProgress
{
    /**
     * Spinner constructor.
     * @param string $format
     * @param array $placeholdersData
     * @param int|null $max
     * @param bool $showImmediately
     * @param OutputInterface|null $output
     */
    public function __construct(
        string $format,
        array $placeholdersData = [],
        int $max = 0,
        bool $showImmediately = true,
        OutputInterface $output = null
    ) {
        $this->initProgressBar($max, $output);

        $this->setFormat($format);

        $this->updatePlaceholders($placeholdersData);

        if ($showImmediately) {
            $this->progressBar->start();
        }
    }

    /**
     * @param array $placeholdersData
     * @return void
     */
    public function advance(array $placeholdersData = []): void
    {
        ++$this->step;

        $this->updatePlaceholders($placeholdersData, false);

        $this->progressBar->advance();
    }
}

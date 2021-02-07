<?php

namespace Savks\Consoler\Support;

use BadMethodCallException;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Formatter
 * @package Savks\Consoler\Support
 *
 * @method Format black()
 * @method Format red()
 * @method Format green()
 * @method Format yellow()
 * @method Format blue()
 * @method Format magenta()
 * @method Format cyan()
 * @method Format white()
 *
 * @method Format bgBlack()
 * @method Format bgRed()
 * @method Format bgGreen()
 * @method Format bgYellow()
 * @method Format bgBlue()
 * @method Format bgMagenta()
 * @method Format bgCyan()
 * @method Format bgWhite()
 *
 * @method Format bold()
 * @method Format underscore()
 * @method Format blink()
 * @method Format reverse()
 * @method Format conceal()
 */
class Format
{
    /**
     * @var string[]
     */
    protected static $supportedColors = [
        'black',
        'red',
        'green',
        'yellow',
        'blue',
        'magenta',
        'cyan',
        'white',
    ];

    /**
     * @var string[]
     */
    protected static $supportedOptions = [
        'bold',
        'underscore',
        'blink',
        'reverse',
        'conceal',
    ];

    /**
     * @var string
     */
    protected $rawText;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var string
     */
    protected $backgroundColor;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $url;

    /**
     * Formatter constructor.
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->rawText = $this->text = $text;
    }

    /**
     * @param string $value
     * @return $this
     */
    protected function color(string $value): Format
    {
        $this->color = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    protected function bgColor(string $value): Format
    {
        $this->backgroundColor = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    protected function addOption(string $value): Format
    {
        if (! in_array($value, $this->options, true)) {
            $this->options[] = $value;
        }

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function hexColor(string $value): Format
    {
        $this->color = '#' . ltrim($value, '#');

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function hexBgColor(string $value): Format
    {
        $this->color = '#' . ltrim($value, '#');

        return $this;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function linkTo(string $url): Format
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return string
     */
    public function __call($name, $arguments)
    {
        $color = Str::startsWith($name, 'bg') ?
            Str::lower(
                substr($name, 2)
            ) :
            $name;

        if ($this->isSupportedColor($color)) {
            if (Str::startsWith($name, 'bg')) {
                return $this->bgColor($color);
            } else {
                return $this->color($color);
            }
        } elseif ($this->isSupportedOption($name)) {
            return $this->addOption($name);
        }

        throw new BadMethodCallException("Invalid method [{$name}]");
    }

    /**
     * @param string $text
     * @return static
     */
    public static function for(string $text): Format
    {
        return new static($text);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $styles = [];

        if ($this->color) {
            $styles[] = "fg={$this->color}";
        }

        if ($this->backgroundColor) {
            $styles[] = "bg={$this->backgroundColor}";
        }

        if ($this->options) {
            $styles[] = "options=" . implode(',', $this->options);
        }

        if ($styles) {
            $result = sprintf(
                '<%s>%s</>',
                implode(';', $styles),
                $this->text
            );
        } else {
            $result = $this->text;
        }

        return $this->url ? "<href={$this->url}>{$result}</>" : $result;
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isSupportedColor($name): bool
    {
        return in_array($name, static::$supportedColors, true);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isSupportedOption($name): bool
    {
        return in_array($name, static::$supportedOptions, true);
    }
}

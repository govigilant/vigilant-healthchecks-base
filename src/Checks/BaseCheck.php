<?php

namespace Vigilant\HealthChecksBase\Checks;

use RuntimeException;
use Vigilant\HealthChecksBase\Data\CheckConfigData;

abstract class BaseCheck
{
    protected string $type = '';

    protected ?string $key = null;

    abstract public function available(): bool;

    public function type(): string
    {
        if (empty($this->type)) {
            throw new RuntimeException('Check type is not set on '.static::class);
        }

        return $this->type;
    }

    public function key(): ?string
    {
        return $this->key;
    }

    public static function configure(mixed ...$arguments): CheckConfigData
    {
        return CheckConfigData::make([
            'class' => static::class,
            'arguments' => array_values($arguments),
        ]);
    }

    public static function make(): CheckConfigData
    {
        return CheckConfigData::make([
            'class' => static::class,
            'arguments' => [],
        ]);
    }

    /**
     * @return static
     */
    public static function build(CheckConfigData $config): BaseCheck
    {
        /** @var static */
        $instance = new ($config->class)(...$config->arguments);

        return $instance;
    }
}

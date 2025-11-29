<?php

namespace Vigilant\HealthChecksBase\Checks;

use RuntimeException;

abstract class BaseCheck
{
    protected string $type = '';

    protected ?string $key = null;

    protected bool $forceAvailable = false;

    abstract public function available(): bool;

    final public function forceAvailable(): bool
    {
        return $this->forceAvailable;
    }

    public function type(): string
    {
        if (empty($this->type)) {
            throw new RuntimeException('Check type is not set on ' . static::class);
        }

        return $this->type;
    }

    public function key(): ?string
    {
        return $this->key;
    }
}

<?php

namespace Vigilant\HealthChecksBase\Checks;

use RuntimeException;

abstract class BaseCheck
{
    protected string $type = '';

    protected ?string $key = null;

    protected bool $forceAvailable = false;

    abstract public function available(): bool;

    public function alwaysRun(bool $available = true): static
    {
        $this->forceAvailable = $available;

        return $this;
    }

    final public function isAvailable(): bool
    {
        if ($this->forceAvailable) {
            return true;
        }

        return $this->available();
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

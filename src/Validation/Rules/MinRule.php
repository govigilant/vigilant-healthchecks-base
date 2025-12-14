<?php

namespace Vigilant\HealthChecksBase\Validation\Rules;

use Vigilant\HealthChecksBase\Validation\ValidationException;

final class MinRule implements Rule
{
    public function __construct(
        private readonly float $min,
        private readonly string $definition,
    ) {}

    public function validate(string $attribute, mixed $value): void
    {
        if (! is_numeric($value) || (float) $value < $this->min) {
            throw ValidationException::invalid($attribute, $this->definition);
        }
    }
}

<?php

namespace Vigilant\HealthChecksBase\Validation\Rules;

use Vigilant\HealthChecksBase\Validation\ValidationException;

final class EnumRule implements Rule
{
    public function __construct(private readonly string $enumClass) {}

    public function validate(string $attribute, mixed $value): void
    {
        if (! $value instanceof $this->enumClass) {
            throw ValidationException::invalid($attribute, 'enum');
        }
    }
}

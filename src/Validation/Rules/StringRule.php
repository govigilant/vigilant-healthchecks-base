<?php

namespace Vigilant\HealthChecksBase\Validation\Rules;

use Vigilant\HealthChecksBase\Validation\ValidationException;

final class StringRule implements Rule
{
    public function validate(string $attribute, mixed $value): void
    {
        if (! is_string($value)) {
            throw ValidationException::invalid($attribute, 'string');
        }
    }
}

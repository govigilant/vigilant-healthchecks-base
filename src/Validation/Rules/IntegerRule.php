<?php

namespace Vigilant\HealthChecksBase\Validation\Rules;

use Vigilant\HealthChecksBase\Validation\ValidationException;

final class IntegerRule implements Rule
{
    public function validate(string $attribute, mixed $value): void
    {
        if (! is_int($value)) {
            throw ValidationException::invalid($attribute, 'integer');
        }
    }
}

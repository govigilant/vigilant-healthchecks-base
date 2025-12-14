<?php

namespace Vigilant\HealthChecksBase\Validation\Rules;

use Vigilant\HealthChecksBase\Validation\ValidationException;

final class ArrayRule implements Rule
{
    public function validate(string $attribute, mixed $value): void
    {
        if (! is_array($value)) {
            throw ValidationException::invalid($attribute, 'array');
        }
    }
}

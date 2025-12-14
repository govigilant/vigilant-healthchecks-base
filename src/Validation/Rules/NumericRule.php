<?php

namespace Vigilant\HealthChecksBase\Validation\Rules;

use Vigilant\HealthChecksBase\Validation\ValidationException;

final class NumericRule implements Rule
{
    public function validate(string $attribute, mixed $value): void
    {
        if (! is_numeric($value)) {
            throw ValidationException::invalid($attribute, 'numeric');
        }
    }
}

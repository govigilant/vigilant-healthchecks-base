<?php

namespace Vigilant\HealthChecksBase\Validation\Rules;

use Vigilant\HealthChecksBase\Validation\ValidationException;

final class RequiredRule implements PresenceRule
{
    public function validatePresence(string $attribute, array $data): void
    {
        if (! array_key_exists($attribute, $data)) {
            throw ValidationException::required($attribute);
        }
    }

    public function allowsNull(): bool
    {
        return false;
    }
}

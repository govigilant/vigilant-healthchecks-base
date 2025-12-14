<?php

namespace Vigilant\HealthChecksBase\Validation\Rules;

final class NullableRule implements PresenceRule
{
    public function validatePresence(string $attribute, array $data): void {}

    public function allowsNull(): bool
    {
        return true;
    }
}

<?php

namespace Vigilant\HealthChecksBase\Validation\Rules;

interface PresenceRule
{
    public function validatePresence(string $attribute, array $data): void;

    public function allowsNull(): bool;
}

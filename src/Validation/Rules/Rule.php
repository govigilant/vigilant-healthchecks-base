<?php

namespace Vigilant\HealthChecksBase\Validation\Rules;

interface Rule
{
    public function validate(string $attribute, mixed $value): void;
}

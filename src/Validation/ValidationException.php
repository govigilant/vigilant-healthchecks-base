<?php

namespace Vigilant\HealthChecksBase\Validation;

use InvalidArgumentException;

class ValidationException extends InvalidArgumentException
{
    public static function required(string $attribute): self
    {
        return new self("The {$attribute} field is required.");
    }

    public static function nullNotAllowed(string $attribute): self
    {
        return new self("The {$attribute} field may not be null.");
    }

    public static function invalid(string $attribute, string $rule): self
    {
        return new self("The {$attribute} field failed the {$rule} validation rule.");
    }

    public static function unknownRule(string $attribute, string $rule): self
    {
        return new self("Unknown validation rule [{$rule}] for {$attribute}.");
    }

    public static function invalidRuleDefinition(string $rule): self
    {
        return new self("Invalid validation rule definition [{$rule}].");
    }
}

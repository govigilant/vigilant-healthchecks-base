<?php

namespace Vigilant\HealthChecksBase\Validation\Rules;

use Vigilant\HealthChecksBase\Validation\ValidationException;

final class RuleFactory
{
    public static function make(string $attribute, string $definition): Rule|PresenceRule
    {
        return match (true) {
            $definition === 'required' => new RequiredRule(),
            $definition === 'nullable' => new NullableRule(),
            $definition === 'string' => new StringRule(),
            $definition === 'integer' => new IntegerRule(),
            $definition === 'numeric' => new NumericRule(),
            $definition === 'array' => new ArrayRule(),
            str_starts_with($definition, 'min:') => self::minRule($definition),
            str_starts_with($definition, 'enum:') => self::enumRule($definition),
            default => throw ValidationException::unknownRule($attribute, $definition),
        };
    }

    private static function minRule(string $definition): MinRule
    {
        $value = substr($definition, 4);

        if ($value === '' || ! is_numeric($value)) {
            throw ValidationException::invalidRuleDefinition($definition);
        }

        return new MinRule((float) $value, $definition);
    }

    private static function enumRule(string $definition): EnumRule
    {
        $enumClass = substr($definition, 5);

        if ($enumClass === '' || ! enum_exists($enumClass)) {
            throw ValidationException::invalidRuleDefinition($definition);
        }

        return new EnumRule($enumClass);
    }
}

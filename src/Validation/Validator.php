<?php

namespace Vigilant\HealthChecksBase\Validation;

use Vigilant\HealthChecksBase\Validation\Rules\PresenceRule;
use Vigilant\HealthChecksBase\Validation\Rules\Rule;
use Vigilant\HealthChecksBase\Validation\Rules\RuleFactory;

final class Validator
{
    public static function validate(array $data, array $rules): void
    {
        if ($rules === []) {
            return;
        }

        foreach ($rules as $attribute => $attributeRules) {
            $attributeRules = is_array($attributeRules) ? $attributeRules : [$attributeRules];
            self::validateAttribute($attribute, $data, $attributeRules);
        }
    }

    private static function validateAttribute(string $attribute, array $data, array $definitions): void
    {
        [$presenceRules, $valueRules] = self::buildRules($attribute, $definitions);

        foreach ($presenceRules as $rule) {
            $rule->validatePresence($attribute, $data);
        }

        if (! array_key_exists($attribute, $data)) {
            return;
        }

        $value = $data[$attribute];

        if ($value === null) {
            if (self::allowsNull($presenceRules)) {
                return;
            }

            throw ValidationException::nullNotAllowed($attribute);
        }

        foreach ($valueRules as $rule) {
            $rule->validate($attribute, $value);
        }
    }

    /**
     * @return array{0: PresenceRule[], 1: Rule[]}
     */
    private static function buildRules(string $attribute, array $definitions): array
    {
        $presenceRules = [];
        $valueRules = [];

        foreach ($definitions as $definition) {
            if (! is_string($definition)) {
                throw ValidationException::invalidRuleDefinition(is_object($definition) ? $definition::class : gettype($definition));
            }

            $rule = RuleFactory::make($attribute, $definition);

            if ($rule instanceof PresenceRule) {
                $presenceRules[] = $rule;
                continue;
            }

            $valueRules[] = $rule;
        }

        return [$presenceRules, $valueRules];
    }

    /**
     * @param PresenceRule[] $presenceRules
     */
    private static function allowsNull(array $presenceRules): bool
    {
        foreach ($presenceRules as $rule) {
            if ($rule->allowsNull()) {
                return true;
            }
        }

        return false;
    }
}

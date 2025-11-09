<?php

namespace Vigilant\HealthChecksBase\Data;

use Vigilant\HealthChecksBase\Checks\BaseCheck;

/**
 * @property-read class-string $class
 * @property-read array<int, mixed> $arguments
 */
class CheckConfigData extends Data
{
    protected function rules(): array
    {
        return [
            'class' => ['required', 'string'],
            'arguments' => ['array'],
        ];
    }

    public function build(): BaseCheck
    {
        return BaseCheck::build($this);
    }
}

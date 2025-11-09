<?php

namespace Vigilant\HealthChecksBase\Data;

use Illuminate\Validation\Rules\Enum;
use Vigilant\HealthChecksBase\Enums\Status;

class MetricData extends Data
{
    protected function rules(): array
    {
        return [
            'type' => ['required', 'string'],
            'key' => ['nullable', 'string'],
            'status' => ['required', new Enum(Status::class)],
            'value' => ['required', 'numeric'],
            'unit' => ['nullable', 'string'],
        ];
    }

    public function type(): string
    {
        return $this->data['type'];
    }

    public function key(): ?string
    {
        return $this->data['key'] ?? null;
    }

    public function value(): float|int
    {
        return $this->data['value'];
    }

    public function unit(): ?string
    {
        return $this->data['unit'] ?? null;
    }

    public function data(): array
    {
        return $this->data;
    }
}

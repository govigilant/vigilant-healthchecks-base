<?php

namespace Vigilant\HealthChecksBase\Data;

use Vigilant\HealthChecksBase\Enums\Status;

class ResultData extends Data
{
    protected function rules(): array
    {
        return [
            'type' => ['required', 'string'],
            'key' => ['nullable', 'string'],
            'status' => ['required', 'enum:' . Status::class],
            'message' => ['nullable', 'string'],
            'data' => ['nullable', 'array'],
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

    public function status(): Status
    {
        return $this->data['status'];
    }

    public function message(): ?string
    {
        return $this->data['message'] ?? null;
    }

    public function data(): ?array
    {
        return $this->data['data'] ?? null;
    }
}

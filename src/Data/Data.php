<?php

namespace Vigilant\HealthChecksBase\Data;

use ArrayAccess;
use Vigilant\HealthChecksBase\Validation\Validator;

/**
 * @implements ArrayAccess<string, mixed>
 */
abstract class Data implements ArrayAccess
{
    protected array $rules = [];

    final public function __construct(public array $data = [])
    {
        $this->rules = $this->rules();
        $this->validate();
    }

    protected function rules(): array
    {
        return [];
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }

    public function __get(string $name): mixed
    {
        return $this->data[$name] ?? null;
    }

    public function validate(): void
    {
        Validator::validate($this->data, $this->rules);
    }

    public static function make(array $data): static
    {
        return new static($data);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}

<?php

namespace Vigilant\HealthChecksBase\Tests\Data;

use Vigilant\HealthChecksBase\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vigilant\HealthChecksBase\Data\Data;

class ConcreteData extends Data
{
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'age' => ['nullable', 'integer', 'min:0'],
        ];
    }
}

class EmptyRulesData extends Data
{
    protected function rules(): array
    {
        return [];
    }
}

class DefaultRulesData extends Data {}

class DataTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_valid_data(): void
    {
        $data = ['name' => 'John'];

        $instance = new ConcreteData($data);

        $this->assertInstanceOf(Data::class, $instance);
        $this->assertEquals($data, $instance->data);
    }

    #[Test]
    public function it_validates_on_construction(): void
    {
        $this->expectException(ValidationException::class);

        new ConcreteData(['age' => 25]);
    }

    #[Test]
    public function it_can_be_created_using_make(): void
    {
        $data = ['name' => 'Jane'];

        $instance = ConcreteData::make($data);

        $this->assertInstanceOf(ConcreteData::class, $instance);
        $this->assertEquals($data, $instance->data);
    }

    #[Test]
    public function it_throws_validation_exception_on_invalid_data(): void
    {
        $this->expectException(ValidationException::class);

        new ConcreteData(['name' => 123]);
    }

    #[Test]
    public function it_passes_validation_with_valid_data(): void
    {
        $instance = new ConcreteData(['name' => 'John', 'age' => 30]);

        $this->assertEquals('John', $instance->data['name']);
        $this->assertEquals(30, $instance->data['age']);
    }

    #[Test]
    public function it_returns_true_for_existing_key(): void
    {
        $instance = new ConcreteData(['name' => 'John']);

        $this->assertTrue(isset($instance['name']));
    }

    #[Test]
    public function it_returns_false_for_non_existing_key(): void
    {
        $instance = new ConcreteData(['name' => 'John']);

        $this->assertFalse(isset($instance['nonexistent']));
    }

    #[Test]
    public function it_returns_value_for_existing_key(): void
    {
        $instance = new ConcreteData(['name' => 'John']);

        $this->assertEquals('John', $instance['name']);
    }

    #[Test]
    public function it_returns_null_for_non_existing_key(): void
    {
        $instance = new ConcreteData(['name' => 'John']);

        $this->assertNull($instance['nonexistent']);
    }

    #[Test]
    public function it_can_set_value_via_array_access(): void
    {
        $instance = new ConcreteData(['name' => 'John']);

        $instance['age'] = 25;

        $this->assertEquals(25, $instance['age']);
    }

    #[Test]
    public function it_can_overwrite_existing_value(): void
    {
        $instance = new ConcreteData(['name' => 'John']);

        $instance['name'] = 'Jane';

        $this->assertEquals('Jane', $instance['name']);
    }

    #[Test]
    public function it_can_unset_key_via_array_access(): void
    {
        $instance = new ConcreteData(['name' => 'John', 'age' => 25]);

        unset($instance['age']);

        $this->assertFalse(isset($instance['age']));
        $this->assertTrue(isset($instance['name']));
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $data = ['name' => 'John', 'age' => 30];
        $instance = new ConcreteData($data);

        $result = $instance->toArray();

        $this->assertEquals($data, $result);
    }

    #[Test]
    public function it_can_be_created_with_empty_data_when_no_rules(): void
    {
        $instance = new EmptyRulesData([]);

        $this->assertInstanceOf(Data::class, $instance);
        $this->assertEquals([], $instance->data);
    }

    #[Test]
    public function it_can_be_created_with_data_when_no_rules(): void
    {
        $data = ['anything' => 'goes', 'no' => 'validation'];
        $instance = new EmptyRulesData($data);

        $this->assertEquals($data, $instance->data);
    }

    #[Test]
    public function it_works_with_null_values(): void
    {
        $instance = new ConcreteData(['name' => 'John', 'age' => null]);

        $this->assertTrue(isset($instance['age']));
        $this->assertNull($instance['age']);
    }

    #[Test]
    public function it_returns_static_instance_from_make(): void
    {
        $instance = ConcreteData::make(['name' => 'Test']);

        $this->assertInstanceOf(ConcreteData::class, $instance);
    }

    #[Test]
    public function it_validates_multiple_rules(): void
    {
        $this->expectException(ValidationException::class);

        new ConcreteData(['name' => 'John', 'age' => -5]);
    }

    #[Test]
    public function it_validates_optional_fields(): void
    {
        $instance = new ConcreteData(['name' => 'John']);

        $this->assertEquals('John', $instance['name']);
        $this->assertNull($instance['age']);
    }

    #[Test]
    public function it_has_default_empty_rules(): void
    {
        $instance = new DefaultRulesData(['any' => 'data']);

        $this->assertInstanceOf(Data::class, $instance);
        $this->assertEquals(['any' => 'data'], $instance->data);
    }
}

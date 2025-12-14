<?php

namespace Vigilant\HealthChecksBase\Tests\Data;

use Vigilant\HealthChecksBase\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vigilant\HealthChecksBase\Data\MetricData;

class MetricDataTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_valid_data(): void
    {
        $data = [
            'type' => 'cpu_usage',
            'key' => 'server-1',
            'value' => 45.5,
            'unit' => 'percent',
        ];

        $metric = new MetricData($data);

        $this->assertInstanceOf(MetricData::class, $metric);
        $this->assertEquals($data, $metric->data);
    }

    #[Test]
    public function it_can_be_created_using_make(): void
    {
        $data = [
            'type' => 'memory',
            'key' => null,
            'value' => 80,
        ];

        $metric = MetricData::make($data);

        $this->assertInstanceOf(MetricData::class, $metric);
        $this->assertEquals($data, $metric->data);
    }

    #[Test]
    public function it_validates_required_type(): void
    {
        $this->expectException(ValidationException::class);

        new MetricData([
            'key' => null,
            'value' => 100,
        ]);
    }

    #[Test]
    public function it_validates_type_is_string(): void
    {
        $this->expectException(ValidationException::class);

        new MetricData([
            'type' => 123,
            'key' => null,
            'value' => 100,
        ]);
    }

    #[Test]
    public function it_validates_required_value(): void
    {
        $this->expectException(ValidationException::class);

        new MetricData([
            'type' => 'test', 'key' => null,
        ]);
    }

    #[Test]
    public function it_validates_value_is_numeric(): void
    {
        $this->expectException(ValidationException::class);

        new MetricData([
            'type' => 'test', 'key' => null,
            'value' => 'not numeric',
        ]);
    }

    #[Test]
    public function it_accepts_integer_value(): void
    {
        $metric = new MetricData([
            'type' => 'test', 'key' => null,
            'value' => 100,
        ]);

        $this->assertEquals(100, $metric['value']);
    }

    #[Test]
    public function it_accepts_float_value(): void
    {
        $metric = new MetricData([
            'type' => 'test', 'key' => null,
            'value' => 45.5,
        ]);

        $this->assertEquals(45.5, $metric['value']);
    }

    #[Test]
    public function it_accepts_numeric_string_value(): void
    {
        $metric = new MetricData([
            'type' => 'test', 'key' => null,
            'value' => '100',
        ]);

        $this->assertEquals('100', $metric['value']);
    }

    #[Test]
    public function it_allows_optional_unit(): void
    {
        $data = [
            'type' => 'test', 'key' => null,
            'value' => 100,
        ];

        $metric = new MetricData($data);

        $this->assertInstanceOf(MetricData::class, $metric);
    }

    #[Test]
    public function it_validates_unit_is_string_when_provided(): void
    {
        $this->expectException(ValidationException::class);

        new MetricData([
            'type' => 'test', 'key' => null,
            'value' => 100,
            'unit' => 123,
        ]);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $data = [
            'type' => 'test',
            'key' => 'server-1',
            'value' => 75,
            'unit' => 'mb',
        ];

        $metric = new MetricData($data);

        $this->assertEquals($data, $metric->toArray());
    }

    #[Test]
    public function it_checks_offset_exists_via_array_access(): void
    {
        $metric = new MetricData([
            'type' => 'test',
            'key' => null,
            'value' => 100,
        ]);

        $this->assertTrue(isset($metric['type']));
        $this->assertTrue(isset($metric['value']));
        $this->assertFalse(isset($metric['nonexistent']));
    }

    #[Test]
    public function it_gets_offset_via_array_access(): void
    {
        $metric = new MetricData([
            'type' => 'test',
            'key' => 'instance-1',
            'value' => 100,
        ]);

        $this->assertEquals('test', $metric['type']);
        $this->assertEquals('instance-1', $metric['key']);
        $this->assertEquals(100, $metric['value']);
        $this->assertNull($metric['nonexistent']);
    }

    #[Test]
    public function it_sets_offset_via_array_access(): void
    {
        $metric = new MetricData([
            'type' => 'test',
            'key' => null,
            'value' => 100,
        ]);

        $metric['unit'] = 'percent';

        $this->assertEquals('percent', $metric['unit']);
    }

    #[Test]
    public function it_unsets_offset_via_array_access(): void
    {
        $metric = new MetricData([
            'type' => 'test',
            'key' => null,
            'value' => 100,
            'unit' => 'percent',
        ]);

        unset($metric['unit']);

        $this->assertFalse(isset($metric['unit']));
    }
}

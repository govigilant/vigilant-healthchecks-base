<?php

namespace Vigilant\HealthChecksBase\Tests\Data;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vigilant\HealthChecksBase\Data\ResultData;
use Vigilant\HealthChecksBase\Enums\Status;
use Vigilant\HealthChecksBase\Validation\ValidationException;

class ResultDataTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_valid_data(): void
    {
        $data = [
            'type' => 'database',
            'key' => 'primary',
            'status' => Status::Healthy,
            'message' => 'Database is operational',
            'data' => ['connections' => 5],
        ];

        $result = new ResultData($data);

        $this->assertInstanceOf(ResultData::class, $result);
        $this->assertEquals($data, $result->data);
    }

    #[Test]
    public function it_can_be_created_using_make(): void
    {
        $data = [
            'type' => 'cache',
            'key' => null,
            'status' => Status::Healthy,
        ];

        $result = ResultData::make($data);

        $this->assertInstanceOf(ResultData::class, $result);
        $this->assertEquals($data, $result->data);
    }

    #[Test]
    public function it_validates_required_type(): void
    {
        $this->expectException(ValidationException::class);

        new ResultData([
            'key' => 'test',
            'status' => Status::Healthy,
        ]);
    }

    #[Test]
    public function it_validates_type_is_string(): void
    {
        $this->expectException(ValidationException::class);

        new ResultData([
            'type' => 123,
            'key' => null,
            'status' => Status::Healthy,
        ]);
    }

    #[Test]
    public function it_allows_optional_key(): void
    {
        $data = [
            'type' => 'test',
            'key' => null,
            'status' => Status::Healthy,
        ];

        $result = new ResultData($data);

        $this->assertInstanceOf(ResultData::class, $result);
        $this->assertNull($result->key());
    }

    #[Test]
    public function it_validates_key_is_string_when_provided(): void
    {
        $this->expectException(ValidationException::class);

        new ResultData([
            'type' => 'test',
            'key' => 123,
            'status' => Status::Healthy,
        ]);
    }

    #[Test]
    public function it_validates_required_status(): void
    {
        $this->expectException(ValidationException::class);

        new ResultData([
            'type' => 'test',
            'key' => null,
        ]);
    }

    #[Test]
    public function it_validates_status_is_enum(): void
    {
        $this->expectException(ValidationException::class);

        new ResultData([
            'type' => 'test',
            'key' => null,
            'status' => 'invalid',
        ]);
    }

    #[Test]
    public function it_allows_optional_message(): void
    {
        $data = [
            'type' => 'test',
            'key' => null,
            'status' => Status::Healthy,
        ];

        $result = new ResultData($data);

        $this->assertInstanceOf(ResultData::class, $result);
    }

    #[Test]
    public function it_validates_message_is_string_when_provided(): void
    {
        $this->expectException(ValidationException::class);

        new ResultData([
            'type' => 'test',
            'key' => null,
            'status' => Status::Healthy,
            'message' => 123,
        ]);
    }

    #[Test]
    public function it_allows_optional_data(): void
    {
        $data = [
            'type' => 'test',
            'key' => null,
            'status' => Status::Healthy,
        ];

        $result = new ResultData($data);

        $this->assertInstanceOf(ResultData::class, $result);
    }

    #[Test]
    public function it_validates_data_is_array_when_provided(): void
    {
        $this->expectException(ValidationException::class);

        new ResultData([
            'type' => 'test',
            'key' => null,
            'status' => Status::Healthy,
            'data' => 'not an array',
        ]);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $data = [
            'type' => 'test',
            'key' => 'instance-1',
            'status' => Status::Healthy,
            'message' => 'Test message',
        ];

        $result = new ResultData($data);

        $this->assertEquals($data, $result->toArray());
    }

    #[Test]
    public function it_checks_offset_exists_via_array_access(): void
    {
        $result = new ResultData([
            'type' => 'test',
            'key' => null,
            'status' => Status::Healthy,
        ]);

        $this->assertTrue(isset($result['type']));
        $this->assertTrue(isset($result['status']));
        $this->assertFalse(isset($result['nonexistent']));
    }

    #[Test]
    public function it_gets_offset_via_array_access(): void
    {
        $result = new ResultData([
            'type' => 'test',
            'key' => 'instance-1',
            'status' => Status::Healthy,
        ]);

        $this->assertEquals('test', $result['type']);
        $this->assertEquals('instance-1', $result['key']);
        $this->assertEquals(Status::Healthy, $result['status']);
        $this->assertNull($result['nonexistent']);
    }

    #[Test]
    public function it_sets_offset_via_array_access(): void
    {
        $result = new ResultData([
            'type' => 'test',
            'key' => null,
            'status' => Status::Healthy,
        ]);

        $result['message'] = 'New message';

        $this->assertEquals('New message', $result['message']);
    }

    #[Test]
    public function it_unsets_offset_via_array_access(): void
    {
        $result = new ResultData([
            'type' => 'test',
            'key' => null,
            'status' => Status::Healthy,
            'message' => 'Test message',
        ]);

        unset($result['message']);

        $this->assertFalse(isset($result['message']));
    }

    #[Test]
    public function it_works_with_all_status_types(): void
    {
        $healthyResult = new ResultData([
            'type' => 'test1',
            'key' => null,
            'status' => Status::Healthy,
        ]);

        $warningResult = new ResultData([
            'type' => 'test2',
            'key' => null,
            'status' => Status::Warning,
        ]);

        $unhealthyResult = new ResultData([
            'type' => 'test3',
            'key' => null,
            'status' => Status::Unhealthy,
        ]);

        $this->assertEquals(Status::Healthy, $healthyResult['status']);
        $this->assertEquals(Status::Warning, $warningResult['status']);
        $this->assertEquals(Status::Unhealthy, $unhealthyResult['status']);
    }
}

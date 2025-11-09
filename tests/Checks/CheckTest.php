<?php

namespace Vigilant\HealthChecksBase\Tests\Checks;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vigilant\HealthChecksBase\Data\ResultData;
use Vigilant\HealthChecksBase\Enums\Status;
use Vigilant\HealthChecksBase\Tests\Fakes\FakeCheck;

class CheckTest extends TestCase
{
    #[Test]
    public function it_returns_result_data_from_run(): void
    {
        $check = new FakeCheck;

        $result = $check->run();

        $this->assertInstanceOf(ResultData::class, $result);
    }

    #[Test]
    public function it_returns_healthy_status_from_run(): void
    {
        $check = new FakeCheck;

        $result = $check->run();

        $this->assertEquals(Status::Healthy, $result['status']);
        $this->assertEquals('fake-check', $result['type']);
        $this->assertNull($result['key']);
        $this->assertEquals('Fake check passed', $result['message']);
    }

    #[Test]
    public function it_can_return_warning_status(): void
    {
        $resultData = ResultData::make([
            'type' => 'warning-check',
            'key' => null,
            'status' => Status::Warning,
            'message' => 'Something is not quite right',
            'data' => ['warning' => 'high load'],
        ]);

        $check = new FakeCheck(result: $resultData);

        $result = $check->run();

        $this->assertEquals(Status::Warning, $result['status']);
        $this->assertEquals('warning-check', $result['type']);
        $this->assertEquals('Something is not quite right', $result['message']);
        $this->assertEquals(['warning' => 'high load'], $result['data']);
    }

    #[Test]
    public function it_can_return_unhealthy_status(): void
    {
        $resultData = ResultData::make([
            'type' => 'unhealthy-check',
            'key' => null,
            'status' => Status::Unhealthy,
            'message' => 'Service is down',
            'data' => ['error' => 'connection refused'],
        ]);

        $check = new FakeCheck(result: $resultData);

        $result = $check->run();

        $this->assertEquals(Status::Unhealthy, $result['status']);
        $this->assertEquals('unhealthy-check', $result['type']);
        $this->assertEquals('Service is down', $result['message']);
        $this->assertEquals(['error' => 'connection refused'], $result['data']);
    }

    #[Test]
    public function it_can_return_result_without_message(): void
    {
        $resultData = ResultData::make([
            'type' => 'no-message-check',
            'key' => null,
            'status' => Status::Healthy,
            'message' => null,
            'data' => null,
        ]);

        $check = new FakeCheck(result: $resultData);

        $result = $check->run();

        $this->assertNull($result['message']);
        $this->assertNull($result['data']);
    }

    #[Test]
    public function it_includes_type_in_result(): void
    {
        $check = new FakeCheck('redis', true);

        $result = $check->run();

        $this->assertEquals('redis', $result['type']);
        $this->assertNull($result['key']);
    }
}

<?php

namespace Vigilant\HealthChecksBase\Tests\Checks;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vigilant\HealthChecksBase\Data\MetricData;
use Vigilant\HealthChecksBase\Data\ResultData;
use Vigilant\HealthChecksBase\Enums\Status;
use Vigilant\HealthChecksBase\Tests\Fakes\FakeMetricCheck;

class MetricCheckTest extends TestCase
{
    #[Test]
    public function it_extends_check(): void
    {
        $check = new FakeMetricCheck;

        $result = $check->run();

        $this->assertInstanceOf(ResultData::class, $result);
    }

    #[Test]
    public function it_returns_metric_data_from_measure(): void
    {
        $check = new FakeMetricCheck;

        $metric = $check->measure();

        $this->assertInstanceOf(MetricData::class, $metric);
    }

    #[Test]
    public function it_returns_numeric_value_from_measure(): void
    {
        $check = new FakeMetricCheck;

        $metric = $check->measure();

        $this->assertEquals(Status::Healthy, $metric['status']);
        $this->assertEquals('fake-metric-check', $metric['type']);
        $this->assertNull($metric['key']);
        $this->assertEquals(100, $metric['value']);
        $this->assertEquals('ms', $metric['unit']);
    }

    #[Test]
    public function it_can_return_custom_metric(): void
    {
        $metricData = MetricData::make([
            'type' => 'cpu-usage',
            'key' => null,
            'status' => Status::Warning,
            'value' => 85.5,
            'unit' => '%',
        ]);

        $check = new FakeMetricCheck(metric: $metricData);

        $metric = $check->measure();

        $this->assertEquals(Status::Warning, $metric['status']);
        $this->assertEquals('cpu-usage', $metric['type']);
        $this->assertEquals(85.5, $metric['value']);
        $this->assertEquals('%', $metric['unit']);
    }

    #[Test]
    public function it_can_return_unhealthy_metric(): void
    {
        $metricData = MetricData::make([
            'type' => 'memory-usage',
            'key' => null,
            'status' => Status::Unhealthy,
            'value' => 95,
            'unit' => '%',
        ]);

        $check = new FakeMetricCheck(metric: $metricData);

        $metric = $check->measure();

        $this->assertEquals(Status::Unhealthy, $metric['status']);
        $this->assertEquals(95, $metric['value']);
    }

    #[Test]
    public function it_can_return_metric_without_unit(): void
    {
        $metricData = MetricData::make([
            'type' => 'count',
            'key' => null,
            'status' => Status::Healthy,
            'value' => 42,
            'unit' => null,
        ]);

        $check = new FakeMetricCheck(metric: $metricData);

        $metric = $check->measure();

        $this->assertNull($metric['unit']);
        $this->assertEquals(42, $metric['value']);
    }

    #[Test]
    public function it_includes_type_in_measure(): void
    {
        $check = new FakeMetricCheck('redis', true);

        $metric = $check->measure();

        $this->assertEquals('redis', $metric['type']);
        $this->assertNull($metric['key']);
    }

    #[Test]
    public function it_supports_both_run_and_measure(): void
    {
        $check = new FakeMetricCheck;

        $result = $check->run();
        $metric = $check->measure();

        $this->assertInstanceOf(ResultData::class, $result);
        $this->assertInstanceOf(MetricData::class, $metric);
    }

    #[Test]
    public function it_accepts_integer_values(): void
    {
        $metricData = MetricData::make([
            'type' => 'request-count',
            'key' => null,
            'status' => Status::Healthy,
            'value' => 1000,
            'unit' => 'requests',
        ]);

        $check = new FakeMetricCheck(metric: $metricData);

        $metric = $check->measure();

        $this->assertIsInt($metric['value']);
        $this->assertEquals(1000, $metric['value']);
    }

    #[Test]
    public function it_accepts_float_values(): void
    {
        $metricData = MetricData::make([
            'type' => 'response-time',
            'key' => null,
            'status' => Status::Healthy,
            'value' => 123.456,
            'unit' => 'ms',
        ]);

        $check = new FakeMetricCheck(metric: $metricData);

        $metric = $check->measure();

        $this->assertIsFloat($metric['value']);
        $this->assertEquals(123.456, $metric['value']);
    }
}

<?php

namespace Vigilant\HealthChecksBase\Tests\Fakes;

use Vigilant\HealthChecksBase\Checks\Metric;
use Vigilant\HealthChecksBase\Data\MetricData;
use Vigilant\HealthChecksBase\Data\ResultData;
use Vigilant\HealthChecksBase\Enums\Status;

class FakeMetricCheck extends Metric
{
    public function __construct(
        protected string $checkType = 'fake-metric-check',
        protected bool $isAvailable = true,
        protected ?ResultData $result = null,
        protected ?MetricData $metric = null,
    ) {
        $this->type = $this->checkType;
    }

    public function available(): bool
    {
        return $this->isAvailable;
    }

    public function run(): ResultData
    {
        return $this->result ?? ResultData::make([
            'type' => $this->type,
            'key' => $this->key(),
            'status' => Status::Healthy,
            'message' => 'Fake metric check passed',
            'data' => null,
        ]);
    }

    public function measure(): MetricData
    {
        return $this->metric ?? MetricData::make([
            'type' => $this->type,
            'key' => $this->key(),
            'status' => Status::Healthy,
            'value' => 100,
            'unit' => 'ms',
        ]);
    }
}

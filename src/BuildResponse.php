<?php

namespace Vigilant\HealthChecksBase;

use Vigilant\HealthChecksBase\Checks\Check;
use Vigilant\HealthChecksBase\Checks\Metric;

class BuildResponse
{
    public function build(
        array $checks,
        array $metrics
    ): array {
        $checkResults = [];
        $metricResults = [];

        foreach ($checks as $check) {
            if ($check instanceof Check && ($check->forceAvailable() || $check->available())) {
                $checkResults[] = $check->run()->toArray();
            }
        }

        foreach ($metrics as $metric) {
            if ($metric instanceof Metric && ($metric->forceAvailable() || $metric->available())) {
                $metricResults[] = $metric->measure()->toArray();
            }
        }

        return [
            'checks' => $checkResults,
            'metrics' => $metricResults,
        ];
    }
}

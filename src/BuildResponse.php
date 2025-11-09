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
            if ($check->available() && $check instanceof Check) {
                $checkResults[] = $check->run()->toArray();
            }
        }

        foreach ($metrics as $metric) {
            if ($metric->available() && $metric instanceof Metric) {
                $metricResults[] = $metric->measure()->toArray();
            }
        }

        return [
            'checks' => $checkResults,
            'metrics' => $metricResults,
        ];
    }
}

<?php

namespace Vigilant\HealthChecksBase;

use Vigilant\HealthChecksBase\Checks\Check;
use Vigilant\HealthChecksBase\Checks\Metric;

class BuildResponse
{
    public function build(
        array $checks,
        array $metricChecks
    ): array {
        /** @var array<int, Check> $checks */
        $checkResults = [];
        /** @var array<int, Metric> $metricChecks */
        $metricResults = [];

        foreach ($checks as $check) {
            $instance = $check->build();

            if ($instance->available() && $instance instanceof Check) {
                $checkResults[] = $instance->run()->toArray();
            }
        }

        foreach ($metricChecks as $metricCheck) {
            $instance = $metricCheck->build();

            if ($instance->available() && $instance instanceof Metric) {
                $metricResults[] = $instance->measure()->toArray();
            }
        }

        return [
            'checks' => $checkResults,
            'metrics' => $metricResults,
        ];
    }
}

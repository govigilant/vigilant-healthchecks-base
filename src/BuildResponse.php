<?php

namespace Vigilant\HealthChecksBase;

use Vigilant\HealthChecksBase\Checks\Check;
use Vigilant\HealthChecksBase\Checks\MetricCheck;
use Vigilant\HealthChecksBase\Data\CheckConfigData;

class BuildResponse
{
    public function build(
        array $checks,
        array $metricChecks
    ): array {
        /** @var array<int, CheckConfigData> $checks */
        $checkResults = [];
        /** @var array<int, CheckConfigData> $metricChecks */
        $metricResults = [];

        foreach ($checks as $check) {
            $instance = $check->build();

            if ($instance->available() && $instance instanceof Check) {
                $checkResults[] = $instance->run()->toArray();
            }
        }

        foreach ($metricChecks as $metricCheck) {
            $instance = $metricCheck->build();

            if ($instance->available() && $instance instanceof MetricCheck) {
                $metricResults[] = $instance->measure()->toArray();
            }
        }

        return [
            'checks' => $checkResults,
            'metrics' => $metricResults,
        ];
    }
}

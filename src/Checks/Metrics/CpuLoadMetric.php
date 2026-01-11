<?php

namespace Vigilant\HealthChecksBase\Checks\Metrics;

use Throwable;
use Vigilant\HealthChecksBase\Checks\Metric;
use Vigilant\HealthChecksBase\Data\MetricData;

class CpuLoadMetric extends Metric
{
    protected string $type = 'cpu_load';

    protected float $warningThreshold = 70;

    protected float $criticalThreshold = 90;

    public function warningThreshold(float $threshold): self
    {
        $this->warningThreshold = $threshold;

        return $this;
    }

    public function criticalThreshold(float $threshold): self
    {
        $this->criticalThreshold = $threshold;

        return $this;
    }

    public function run(): MetricData
    {
        return $this->measure();
    }

    public function measure(): MetricData
    {
        try {
            $load = sys_getloadavg();

            if ($load === false) {
                return MetricData::make([
                    'type' => $this->type(),
                    'key' => $this->key(),
                    'value' => 0,
                    'unit' => '%',
                ]);
            }

            $cpuCount = $this->getCpuCount();

            if ($cpuCount === null) {
                return MetricData::make([
                    'type' => $this->type(),
                    'value' => 0,
                    'unit' => '%',
                ]);
            }

            $percentage = ($load[0] / $cpuCount) * 100;

            return MetricData::make([
                'type' => $this->type(),
                'value' => round($percentage, 2),
                'unit' => '%',
            ]);
        } catch (Throwable) {
            return MetricData::make([
                'type' => $this->type(),
                'value' => 0,
                'unit' => '%',
            ]);
        }
    }

    protected function getCpuCount(): ?int
    {
        $os = strtoupper(PHP_OS_FAMILY);

        if ($os === 'LINUX' || $os === 'BSD' || $os === 'DARWIN') {
            if (is_readable('/proc/cpuinfo')) {
                $cpuinfo = file_get_contents('/proc/cpuinfo');
                if ($cpuinfo !== false) {
                    $count = substr_count($cpuinfo, 'processor');
                    if ($count > 0) {
                        return $count;
                    }
                }
            }

            if (is_readable('/sys/devices/system/cpu/present')) {
                $present = file_get_contents('/sys/devices/system/cpu/present');
                if ($present !== false && preg_match('/^0-(\d+)$/', trim($present), $matches)) {
                    return (int) $matches[1] + 1;
                }
            }
        }

        if ($os === 'WINDOWS') {
            $processors = getenv('NUMBER_OF_PROCESSORS');
            if ($processors !== false && is_numeric($processors)) {
                return (int) $processors;
            }
        }

        return null;
    }

    public function available(): bool
    {
        return function_exists('sys_getloadavg') && $this->getCpuCount() !== null;
    }
}

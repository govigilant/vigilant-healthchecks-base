<?php

namespace Vigilant\HealthChecksBase\Checks;

use Vigilant\HealthChecksBase\Data\MetricData;

abstract class MetricCheck extends BaseCheck
{
    abstract public function measure(): MetricData;
}

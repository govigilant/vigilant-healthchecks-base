<?php

namespace Vigilant\HealthChecksBase\Checks;

use Vigilant\HealthChecksBase\Data\ResultData;

abstract class Check extends BaseCheck
{
    abstract public function run(): ResultData;
}

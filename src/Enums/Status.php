<?php

namespace Vigilant\HealthChecksBase\Enums;

enum Status: string
{
    case Healthy = 'healthy';
    case Warning = 'warning';
    case Unhealthy = 'unhealthy';
}

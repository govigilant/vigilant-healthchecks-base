# Vigilant Healthchecks Base

This package provides common functionality for implementing healthchecks for [Vigilant](https://github.com/govigilant/vigilant) in various PHP based platforms and frameworks.

## Overview

Vigilant supports two types of healthchecks:
- **Check**: A check that returns unhealthy, warning, or healthy status
- **Metric**: A check that returns a numeric value

This package provides base classes for both types of healthchecks that can be extended to implement custom healthchecks.
It also provides a way to build responses for Vigilant to consume.

## Features

### Base Classes

#### Check
Extend the `Check` class to create status-based healthchecks:

```php
use Vigilant\HealthChecksBase\Checks\Check;
use Vigilant\HealthChecksBase\Data\ResultData;
use Vigilant\HealthChecksBase\Enums\Status;

class DatabaseCheck extends Check
{
    protected string $type = 'database';

    public function available(): bool
    {
        return true; // Check if this healthcheck is available
    }

    public function run(): ResultData
    {
        // Perform your check logic here
        $isConnected = $this->checkDatabaseConnection();

        return ResultData::make([
            'type' => $this->type,
            'key' => $this->key(),
            'status' => $isConnected ? Status::Healthy : Status::Unhealthy,
            'message' => $isConnected ? 'Database is connected' : 'Cannot connect to database',
            'data' => ['host' => 'localhost', 'port' => 3306], // Optional additional data
        ]);
    }
}
```

#### MetricCheck
Extend the `MetricCheck` class to create metric-based healthchecks. The key can be used to differentiate multiple instances of the same metric type.

```php
use Vigilant\HealthChecksBase\Checks\MetricCheck;
use Vigilant\HealthChecksBase\Data\MetricData;

class ResponseTimeCheck extends MetricCheck
{
    protected string $type = 'response-time';

    public function available(): bool
    {
        return true;
    }

    public function measure(): MetricData
    {
        $responseTime = $this->measureResponseTime();

        return MetricData::make([
            'type' => $this->type,
            'key' => $this->key(),
            'value' => $responseTime,
            'unit' => 'ms', // Optional unit
        ]);
    }
}
```

### Data Classes

#### ResultData
Used for check results with validation:

```php
use Vigilant\HealthChecksBase\Data\ResultData;
use Vigilant\HealthChecksBase\Enums\Status;

$result = ResultData::make([
    'type' => 'database',
    'key' => 'mysql-primary',
    'status' => Status::Healthy,
    'message' => 'Connected',
    'data' => ['connections' => 5],
]);
```

#### MetricData
Used for metric results with validation:

```php
use Vigilant\HealthChecksBase\Data\MetricData;

$metric = MetricData::make([
    'type' => 'cpu-usage',
    'key' => 'server-1',
    'value' => 45.2,
    'unit' => 'percent',
]);
```

### Configuration Methods

Checks can be configured using static methods:

```php
// Simple configuration without arguments
$config = DatabaseCheck::make();

// Configuration with constructor arguments
$config = DatabaseCheck::configure('localhost', 3306);

// Add a unique key to differentiate multiple instances
$config = DatabaseCheck::configure('localhost', 3306)->key('mysql-primary');
```

### BuildResponse

The `BuildResponse` class builds the final response for Vigilant:

```php
use Vigilant\HealthChecksBase\BuildResponse;

$builder = new BuildResponse();

$response = $builder->build(
    checks: [
        DatabaseCheck::make(),
        CacheCheck::configure('redis')->key('cache-1'),
    ],
    metricChecks: [
        ResponseTimeCheck::make(),
        MemoryUsageCheck::make(),
    ]
);
```

### Availability Checking

The `available()` method determines if a check should run:

```php
class RedisCheck extends Check
{
    public function available(): bool
    {
        // Only run this check if Redis extension is loaded
        return extension_loaded('redis');
    }

    public function run(): ResultData
    {
        // This will only be called if available() returns true
        // ...
    }
}
```

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Vincent Boon](https://github.com/VincentBean)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


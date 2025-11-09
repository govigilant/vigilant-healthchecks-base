<?php

namespace Vigilant\HealthChecksBase\Tests\Fakes;

use Vigilant\HealthChecksBase\Checks\Check;
use Vigilant\HealthChecksBase\Data\ResultData;
use Vigilant\HealthChecksBase\Enums\Status;

class FakeCheck extends Check
{
    public function __construct(
        protected string $checkType = 'fake-check',
        protected bool $isAvailable = true,
        protected ?ResultData $result = null,
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
            'message' => 'Fake check passed',
            'data' => null,
        ]);
    }
}

<?php

namespace Vigilant\HealthChecksBase\Tests\Enums;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vigilant\HealthChecksBase\Enums\Status;

class StatusTest extends TestCase
{
    #[Test]
    public function it_has_healthy_case(): void
    {
        $this->assertEquals('healthy', Status::Healthy->value);
    }

    #[Test]
    public function it_has_warning_case(): void
    {
        $this->assertEquals('warning', Status::Warning->value);
    }

    #[Test]
    public function it_has_unhealthy_case(): void
    {
        $this->assertEquals('unhealthy', Status::Unhealthy->value);
    }

    #[Test]
    public function it_can_get_all_cases(): void
    {
        $cases = Status::cases();

        $this->assertCount(3, $cases);
        $this->assertContains(Status::Healthy, $cases);
        $this->assertContains(Status::Warning, $cases);
        $this->assertContains(Status::Unhealthy, $cases);
    }

    #[Test]
    public function it_can_be_instantiated_from_string(): void
    {
        $this->assertEquals(Status::Healthy, Status::from('healthy'));
        $this->assertEquals(Status::Warning, Status::from('warning'));
        $this->assertEquals(Status::Unhealthy, Status::from('unhealthy'));
    }

    #[Test]
    public function it_can_try_from_string(): void
    {
        $this->assertEquals(Status::Healthy, Status::tryFrom('healthy'));
        $this->assertEquals(Status::Warning, Status::tryFrom('warning'));
        $this->assertEquals(Status::Unhealthy, Status::tryFrom('unhealthy'));
        $this->assertSame(null, Status::tryFrom('invalid'));
    }
}

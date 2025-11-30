<?php

namespace Vigilant\HealthChecksBase\Tests\Checks;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Vigilant\HealthChecksBase\Tests\Fakes\FakeCheck;

class BaseCheckTest extends TestCase
{
    #[Test]
    public function it_returns_set_type(): void
    {
        $check = FakeCheck::make(checkType: 'test-check');

        $this->assertEquals('test-check', $check->type());
    }

    #[Test]
    public function it_throws_exception_when_type_is_not_set(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Check type is not set on');

        $check = new class extends FakeCheck
        {
            public function __construct()
            {
                $this->type = '';
            }
        };

        $check->type();
    }

    #[Test]
    public function it_returns_null_when_key_is_not_set(): void
    {
        $check = FakeCheck::make();

        $this->assertNull($check->key());
    }

    #[Test]
    public function it_returns_availability_status(): void
    {
        $availableCheck = FakeCheck::make(isAvailable: true);
        $unavailableCheck = FakeCheck::make(isAvailable: false);

        $this->assertTrue($availableCheck->available());
        $this->assertFalse($unavailableCheck->available());
    }
}

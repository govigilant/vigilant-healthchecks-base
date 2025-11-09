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
        $check = new FakeCheck(checkType: 'test-check');

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
        $check = new FakeCheck;

        $this->assertNull($check->key());
    }

    #[Test]
    public function it_returns_availability_status(): void
    {
        $availableCheck = new FakeCheck(isAvailable: true);
        $unavailableCheck = new FakeCheck(isAvailable: false);

        $this->assertTrue($availableCheck->available());
        $this->assertFalse($unavailableCheck->available());
    }
}

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

    #[Test]
    public function it_configures_check_with_arguments(): void
    {
        $config = FakeCheck::configure('test-check', true);

        $this->assertEquals(FakeCheck::class, $config->class);
        $this->assertEquals(['test-check', true], $config->arguments);
        $this->assertNull($config->key);
    }

    #[Test]
    public function it_configures_check_with_fluent_key(): void
    {
        $config = FakeCheck::configure('test-check', true)->key('custom-key');

        $this->assertEquals(FakeCheck::class, $config->class);
        $this->assertEquals('custom-key', $config->key);
    }

    #[Test]
    public function it_configures_check_with_no_arguments(): void
    {
        $config = FakeCheck::configure();

        $this->assertEquals(FakeCheck::class, $config->class);
        $this->assertEmpty($config->arguments);
    }

    #[Test]
    public function it_builds_instance_from_config(): void
    {
        $config = FakeCheck::configure('custom-type', false);
        $check = FakeCheck::build($config);

        $this->assertInstanceOf(FakeCheck::class, $check);
        $this->assertEquals('custom-type', $check->type());
        $this->assertNull($check->key());
        $this->assertFalse($check->available());
    }

    #[Test]
    public function it_builds_instance_with_configured_key(): void
    {
        $config = FakeCheck::configure('custom-type', false)->key('instance-key');
        $check = FakeCheck::build($config);

        $this->assertInstanceOf(FakeCheck::class, $check);
        $this->assertEquals('custom-type', $check->type());
        $this->assertEquals('instance-key', $check->key());
        $this->assertFalse($check->available());
    }

    #[Test]
    public function it_makes_check_config_with_no_arguments(): void
    {
        $config = FakeCheck::make();

        $this->assertEquals(FakeCheck::class, $config->class);
        $this->assertEmpty($config->arguments);
    }

    #[Test]
    public function it_builds_instance_from_make_config(): void
    {
        $config = FakeCheck::make();
        $check = FakeCheck::build($config);

        $this->assertInstanceOf(FakeCheck::class, $check);
        $this->assertEquals('fake-check', $check->type());
        $this->assertNull($check->key());
        $this->assertTrue($check->available());
    }
}

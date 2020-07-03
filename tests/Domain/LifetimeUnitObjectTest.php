<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\FreeNAS\Domain\LifetimeUnit;
use SandwaveIo\FreeNAS\Exceptions\UnexpectedValueException;

/** @covers \SandwaveIo\FreeNAS\Domain\LifetimeUnit */
class LifetimeUnitObjectTest extends TestCase
{
    public function test_create_lifetime_unit_from_helper_functions(): void
    {
        $this->assertSame(LifetimeUnit::UNIT_HOUR, (string) LifetimeUnit::hour());
        $this->assertSame(LifetimeUnit::UNIT_DAY, (string) LifetimeUnit::day());
        $this->assertSame(LifetimeUnit::UNIT_WEEK, (string) LifetimeUnit::week());
        $this->assertSame(LifetimeUnit::UNIT_MONTH, (string) LifetimeUnit::month());
        $this->assertSame(LifetimeUnit::UNIT_YEAR, (string) LifetimeUnit::year());
    }

    public function test_validation_for_unsupported_types(): void
    {
        $this->expectException(UnexpectedValueException::class);
        LifetimeUnit::fromString('not a real unit');
    }

    public function test_is_equal(): void
    {
        $this->assertTrue(LifetimeUnit::hour()->isEqual(LifetimeUnit::hour()));
        $this->assertTrue(((string) LifetimeUnit::hour()) === ((string) LifetimeUnit::hour()));

        $this->assertFalse(LifetimeUnit::hour()->isEqual(LifetimeUnit::day()));
        $this->assertFalse(((string) LifetimeUnit::hour()) === ((string) LifetimeUnit::day()));
    }
}

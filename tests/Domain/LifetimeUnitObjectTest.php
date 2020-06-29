<?php

namespace PCextreme\FreeNAS\Tests\Domain;

use PCextreme\FreeNAS\Domain\LifetimeUnit;
use PCextreme\FreeNAS\Exceptions\UnexpectedValueException;
use PHPUnit\Framework\TestCase;

/** @covers \PCextreme\FreeNAS\Domain\LifetimeUnit */
class LifetimeUnitObjectTest extends TestCase
{
    public function test_create_lifetime_unit_from_helper_functions()
    {
        $this->assertSame(LifetimeUnit::UNIT_HOUR, (string) LifetimeUnit::hour());
        $this->assertSame(LifetimeUnit::UNIT_DAY, (string) LifetimeUnit::day());
        $this->assertSame(LifetimeUnit::UNIT_WEEK, (string) LifetimeUnit::week());
        $this->assertSame(LifetimeUnit::UNIT_MONTH, (string) LifetimeUnit::month());
        $this->assertSame(LifetimeUnit::UNIT_YEAR, (string) LifetimeUnit::year());
    }

    public function test_validation_for_unsupported_types()
    {
        $this->expectException(UnexpectedValueException::class);
        LifetimeUnit::fromString('not a real unit');
    }

    public function test_is_equal()
    {
        $this->assertTrue(LifetimeUnit::hour()->isEqual(LifetimeUnit::hour()));
        $this->assertTrue(((string) LifetimeUnit::hour()) === ((string) LifetimeUnit::hour()));

        $this->assertFalse(LifetimeUnit::hour()->isEqual(LifetimeUnit::day()));
        $this->assertFalse(((string) LifetimeUnit::hour()) === ((string) LifetimeUnit::day()));
    }
}
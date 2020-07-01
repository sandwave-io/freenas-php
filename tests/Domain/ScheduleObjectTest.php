<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests\Domain;

use PCextreme\FreeNAS\Domain\Schedule;
use PCextreme\FreeNAS\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** @covers \PCextreme\FreeNAS\Domain\Schedule */
class ScheduleObjectTest extends TestCase
{
    public function test_create_schedule_from_cron()
    {
        $schedule = Schedule::fromCronDefinition('* * * * *');
        $this->assertInstanceOf(Schedule::class, $schedule);
    }

    public function test_create_schedule_from_invalid_cron()
    {
        $this->expectException(InvalidArgumentException::class);
        Schedule::fromCronDefinition('* * * *');
    }

    public function test_create_schedule_get_properties()
    {
        $schedule = Schedule::fromCronDefinition('1 2 3 4 5');

        $this->assertSame([
            'minute' => '1',
            'hour' => '2',
            'dom' => '3',
            'month' => '4',
            'dow' => '5',
            'begin' => '00:00',
            'end' => '23:59',
        ], $schedule->toArray());
    }

    public function test_create_schedule_from_array()
    {
        $schedule = Schedule::fromArray([
            'minute' => '1',
            'hour' => '2',
            'dom' => '3',
            'month' => '4',
            'dow' => '5',
            'begin' => '00:00',
            'end' => '23:59',
        ]);

        $this->assertInstanceOf(Schedule::class, $schedule);
    }

    public function test_cron_preset_every_minute()
    {
        $schedule = Schedule::everyMinutes(2);
        $this->assertSame('0/2', $schedule->getMinutes());
        $this->assertSame('*', $schedule->getHours());
        $this->assertSame('*', $schedule->getDayOfTheMonth());
        $this->assertSame('*', $schedule->getMonth());
        $this->assertSame('*', $schedule->getDayOfTheWeek());
    }

    public function test_cron_preset_every_hour()
    {
        $schedule = Schedule::everyHours(2);
        $this->assertSame('0', $schedule->getMinutes());
        $this->assertSame('0/2', $schedule->getHours());
        $this->assertSame('*', $schedule->getDayOfTheMonth());
        $this->assertSame('*', $schedule->getMonth());
        $this->assertSame('*', $schedule->getDayOfTheWeek());
    }

    public function test_cron_preset_every_day()
    {
        $schedule = Schedule::everyDay();
        $this->assertSame('0', $schedule->getMinutes());
        $this->assertSame('0', $schedule->getHours());
        $this->assertSame('*', $schedule->getDayOfTheMonth());
        $this->assertSame('*', $schedule->getMonth());
        $this->assertSame('*', $schedule->getDayOfTheWeek());
    }

    public function test_cron_preset_every_week()
    {
        $schedule = Schedule::everyWeek();
        $this->assertSame('0', $schedule->getMinutes());
        $this->assertSame('0', $schedule->getHours());
        $this->assertSame('*', $schedule->getDayOfTheMonth());
        $this->assertSame('*', $schedule->getMonth());
        $this->assertSame('0', $schedule->getDayOfTheWeek());
    }

    public function test_cron_preset_every_month()
    {
        $schedule = Schedule::everyMonth();
        $this->assertSame('0', $schedule->getMinutes());
        $this->assertSame('0', $schedule->getHours());
        $this->assertSame('0', $schedule->getDayOfTheMonth());
        $this->assertSame('*', $schedule->getMonth());
        $this->assertSame('*', $schedule->getDayOfTheWeek());
    }
}

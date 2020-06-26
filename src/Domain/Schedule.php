<?php

namespace PCextreme\FreeNAS\Domain;

use PCextreme\FreeNAS\Exceptions\InvalidArgumentException;

class Schedule
{
    /** @var string */
    private $minute;

    /** @var string */
    private $hour;

    /** @var string */
    private $dayOfTheMonth;

    /** @var string */
    private $month;

    /** @var string */
    private $dayOfTheWeek;

    /** @var string */
    private $begin;

    /** @var string string */
    private $end;

    public function __construct(
        string $minute = '00',
        string $hour = '*',
        string $dayOfTheMonth = '*',
        string $month = '*',
        string $dayOfTheWeek = '*',
        string $begin = '00:00',
        string $end = '23:59'
    ) {
        $this->minute = $minute;
        $this->hour = $hour;
        $this->dayOfTheMonth = $dayOfTheMonth;
        $this->month = $month;
        $this->dayOfTheWeek = $dayOfTheWeek;
        $this->begin = $begin;
        $this->end = $end;
    }

    public static function fromCronDefinition(string $definition): Schedule
    {
        $parts = explode(' ', $definition, 5);

        if (count($parts) !== 5) {
            throw new InvalidArgumentException("Cannot parse cron definition: '{$definition}'");
        }

        [$minute, $hour, $dayOfTheMonth, $month, $dayOfTheWeek] = $parts;

        return new Schedule($minute, $hour, $dayOfTheMonth, $month, $dayOfTheWeek);
    }

    public static function everyMinutes(int $minutes): Schedule
    {
        return Schedule::fromCronDefinition("0/{$minutes} * * * *");
    }

    public static function everHours(int $hours): Schedule
    {
        return Schedule::fromCronDefinition("0 0/{$hours} * * *");
    }

    public static function everyDay(): Schedule
    {
        return Schedule::fromCronDefinition("0 0 * * *");
    }

    public static function everyWeek(): Schedule
    {
        return Schedule::fromCronDefinition("0 0 * * 0");
    }

    public static function everyMonth(): Schedule
    {
        return Schedule::fromCronDefinition("0 0 0 * *");
    }

    public static function fromArray(array $data): Schedule
    {
        return new Schedule(
            $data['minute'],
            $data['hour'],
            $data['dom'],
            $data['month'],
            $data['dow'],
            $data['begin'],
            $data['end']
        );
    }

    public function toArray(): array
    {
        return [
            'minute' => $this->minute,
            'hour' => $this->hour,
            'dom' => $this->dayOfTheMonth,
            'month' => $this->month,
            'dow' => $this->dayOfTheWeek,
            'begin' => $this->begin,
            'end' => $this->end
        ];
    }
}
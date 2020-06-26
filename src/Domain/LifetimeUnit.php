<?php


namespace PCextreme\FreeNAS\Domain;


use PCextreme\FreeNAS\Exceptions\UnexpectedValueException;

class LifetimeUnit
{
    const UNIT_HOUR = 'HOUR';
    const UNIT_DAY = 'DAY';
    const UNIT_WEEK = 'WEEK';
    const UNIT_MONTH = 'MONTH';
    const UNIT_YEAR = 'YEAR';

    /** @var string */
    private $value;

    public function __construct(string $unit)
    {
        $this->value = $unit;
    }

    public static function fromString(string $unit): LifetimeUnit
    {
        $supportedUnits = [
            LifetimeUnit::UNIT_HOUR,
            LifetimeUnit::UNIT_DAY,
            LifetimeUnit::UNIT_WEEK,
            LifetimeUnit::UNIT_MONTH,
            LifetimeUnit::UNIT_YEAR,
        ];

        if (! in_array($unit, $supportedUnits)) {
            throw new UnexpectedValueException("Lifetime unit {$unit} not supported, supported units: ".implode(', ', $supportedUnits));
        }

        return new LifetimeUnit($unit);
    }

    public static function hour(): LifetimeUnit
    {
        return LifetimeUnit::fromString(LifetimeUnit::UNIT_HOUR);
    }

    public static function day(): LifetimeUnit
    {
        return LifetimeUnit::fromString(LifetimeUnit::UNIT_DAY);
    }

    public static function week(): LifetimeUnit
    {
        return LifetimeUnit::fromString(LifetimeUnit::UNIT_WEEK);
    }

    public static function month(): LifetimeUnit
    {
        return LifetimeUnit::fromString(LifetimeUnit::UNIT_MONTH);
    }

    public static function year(): LifetimeUnit
    {
        return LifetimeUnit::fromString(LifetimeUnit::UNIT_YEAR);
    }

    public function isEqual(LifetimeUnit $unit): bool
    {
        return $this->getValue() === $unit->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
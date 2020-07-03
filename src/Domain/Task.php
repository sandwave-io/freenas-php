<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Domain;

class Task
{
    /** @var int */
    private $id;

    /** @var string */
    private $dataset;

    /** @var int */
    private $lifetimeValue;

    /** @var LifetimeUnit */
    private $lifetimeUnit;

    /** @var bool */
    private $isRecursive;

    /** @var Schedule */
    private $schedule;

    public function __construct(
        int $id,
        string $dataset,
        int $lifetimeValue,
        LifetimeUnit $lifetimeUnit,
        bool $isRecursive,
        Schedule $schedule
    ) {
        $this->id = $id;
        $this->dataset = $dataset;
        $this->lifetimeValue = $lifetimeValue;
        $this->lifetimeUnit = $lifetimeUnit;
        $this->isRecursive = $isRecursive;
        $this->schedule = $schedule;
    }

    public static function fromArray(array $data): Task
    {
        return new Task(
            $data['id'],
            $data['dataset'],
            $data['lifetime_value'],
            LifetimeUnit::fromString($data['lifetime_unit']),
            $data['recursive'],
            Schedule::fromArray($data['schedule'])
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDataset(): string
    {
        return $this->dataset;
    }

    public function getLifetimeValue(): int
    {
        return $this->lifetimeValue;
    }

    public function getLifetimeUnit(): LifetimeUnit
    {
        return $this->lifetimeUnit;
    }

    public function isRecursive(): bool
    {
        return $this->isRecursive;
    }
}

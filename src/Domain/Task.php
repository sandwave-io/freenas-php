<?php


namespace PCextreme\FreeNAS\Domain;


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



    public function __construct(
        int $id,
        string $dataset,
        int $lifetimeValue,
        LifetimeUnit $lifetimeUnit,
        bool $isRecursive
    ) {
        $this->id = $id;
        $this->dataset = $dataset;
        $this->lifetimeValue = $lifetimeValue;
        $this->lifetimeUnit = $lifetimeUnit;
        $this->isRecursive = $isRecursive;
    }

    public static function fromArray(array $data): Task
    {
        return new Task(
            $data['id'],
            $data['dataset'],
            $data['lifetime_value'],
            LifetimeUnit::fromString($data['lifetime_unit']),
            $data['recursive']
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

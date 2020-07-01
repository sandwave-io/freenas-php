<?php


namespace PCextreme\FreeNAS\Domain;


class Dataset
{
    const TYPE_FILESYSTEM = 'FILESYSTEM';
    const TYPE_VOLUME = 'VOLUME';

    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $pool;

    /** @var string */
    private $type;

    /** @var string */
    private $mountpoint;

    public function __construct(string $id, string $name, string $pool, string $type, string $mountpoint)
    {
        $this->id = $id;
        $this->name = $name;
        $this->pool = $pool;
        $this->type = $type;
        $this->mountpoint = $mountpoint;
    }

    public static function fromArray(array $data): Dataset
    {
        return new Dataset(
            $data['id'],
            $data['name'],
            $data['pool'],
            $data['type'],
            $data['mountpoint']
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPool(): string
    {
        return $this->pool;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMountPoint(): string
    {
        return $this->mountpoint;
    }
}

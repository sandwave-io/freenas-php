<?php


namespace PCextreme\FreeNAS\Domain;


use PCextreme\FreeNAS\Exceptions\UnexpectedValueException;

class Pool
{
    /** @var string */
    private $name;

    /** @var string */
    private $path;

    public function __construct(string $name, string $path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    public static function fromArray(array $data): Pool
    {
        return new Pool(
            $data['name'],
            $data['path']
        );
    }
}

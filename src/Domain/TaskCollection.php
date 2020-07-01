<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Domain;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

class TaskCollection implements ArrayAccess, IteratorAggregate, Countable
{
    /** @var array<Task> */
    private $collection;

    /** @param array<Task> $collection */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public static function fromArray(array $data): TaskCollection
    {
        $collection = array_map(
            function (array $dataset) {
                return Task::fromArray($dataset);
            },
            $data
        );

        return new TaskCollection($collection);
    }

    /**
     * @param int $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->collection[$offset]);
    }

    /**
     * @param int $offset
     */
    public function offsetGet($offset): ?Task
    {
        return isset($this->collection[$offset]) ? $this->collection[$offset] : null;
    }

    /**
     * @param int|null $offset
     * @param Task     $value
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->collection[] = $value;
        } else {
            $this->collection[$offset] = $value;
        }
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->collection[$offset]);
    }

    /**
     * @return ArrayIterator<Task>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->collection);
    }

    public function count(): int
    {
        return count($this->collection);
    }
}

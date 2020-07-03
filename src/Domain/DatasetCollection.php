<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Domain;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

class DatasetCollection implements ArrayAccess, IteratorAggregate, Countable
{
    /** @var array<Dataset> */
    private $collection;

    /** @param array<Dataset> $collection */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public static function fromArray(array $data): DatasetCollection
    {
        $collection = array_filter(
            $data['children'] ?? [],
            function ($dataset) {
                return $dataset['type'] === Dataset::TYPE_FILESYSTEM;
            }
        );
        $collection = array_map(
            function (array $dataset) {
                return Dataset::fromArray($dataset);
            },
            $collection
        );

        return new DatasetCollection($collection);
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
    public function offsetGet($offset): ?Dataset
    {
        return isset($this->collection[$offset]) ? $this->collection[$offset] : null;
    }

    /**
     * @param int|null $offset
     * @param Dataset  $value
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
     * @return ArrayIterator<Dataset>
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

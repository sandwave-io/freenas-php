<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests\Domain;

use PCextreme\FreeNAS\Domain\Task;
use PCextreme\FreeNAS\Domain\TaskCollection;
use PHPUnit\Framework\TestCase;

/** @covers \PCextreme\FreeNAS\Domain\TaskCollection */
class TaskCollectionTest extends TestCase
{
    public function test_create_dataset_collection_from_array(): void
    {
        $data = json_decode((string) file_get_contents(__DIR__ . '/../json/task_index.json'), true);

        $collection = TaskCollection::fromArray($data);

        $this->assertSame(7, $collection->count(), 'Collection supports count.');

        foreach ($collection as $dataset) {
            $this->assertInstanceOf(Task::class, $dataset, 'Collection is iterable and contains datasets.');
            break;
        }

        $this->assertTrue(isset($collection[1]), 'The isset() works on the collection.');
        assert($collection[1] !== null);
        assert($collection[2] !== null);

        $this->assertNotSame($collection[1]->getId(), $collection[2]->getId(), 'Different indexes have different values.');
        $collection[1] = $collection[2];
        $this->assertSame($collection[1]->getId(), $collection[2]->getId(), 'Different indexes have same values after assigning.');

        $currentSize = $collection->count();
        $this->assertNull($collection[$currentSize], 'Index of size should be null.');
        $collection[] = $collection[2];
        $this->assertNotNull($collection[$currentSize], 'Data should have been pushed to new index.');

        unset($collection[$currentSize]);
        $this->assertNull($collection[$currentSize], 'Index of should be unset now.');
    }
}

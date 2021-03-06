<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\FreeNAS\Domain\Dataset;
use SandwaveIo\FreeNAS\Domain\DatasetCollection;

/** @covers \SandwaveIo\FreeNAS\Domain\DatasetCollection */
class DatasetCollectionTest extends TestCase
{
    public function test_create_dataset_collection_from_array(): void
    {
        $data = json_decode((string) file_get_contents(__DIR__ . '/../json/dataset_index.json'), true);

        $collection = DatasetCollection::fromArray($data);

        $this->assertSame(12, $collection->count(), 'Collection supports count.');

        foreach ($collection as $dataset) {
            $this->assertInstanceOf(Dataset::class, $dataset, 'Collection is iterable and contains datasets.');
            break;
        }

        $this->assertTrue(isset($collection[1]), 'The isset() works on the collection.');
        $this->assertNotNull($collection[1], 'The item exists within the collection.');
        assert($collection[1] !== null);
        assert($collection[2] !== null);

        $this->assertNotSame($collection[1]->getId(), $collection[2]->getId(), 'Different indexes have different values.');
        $collection[1] = $collection[2];
        $this->assertSame($collection[1]->getId(), $collection[2]->getId(), 'Different indexes have same values after assigning.');

        $currentSize = $collection->count();
        $this->assertNull($collection[$currentSize + 1], 'Index of size should be null.');
        $collection[] = $collection[2];
        $this->assertNotNull($collection[$currentSize + 1], 'Data should have been pushed to new index.');

        unset($collection[$currentSize + 1]);
        $this->assertNull($collection[$currentSize + 1], 'Index of should be unset now.');
    }
}

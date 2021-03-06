<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\FreeNAS\Domain\Dataset;

/** @covers \SandwaveIo\FreeNAS\Domain\Dataset */
class DatasetObjectTest extends TestCase
{
    public function test_create_dataset_from_array(): void
    {
        $data = json_decode((string) file_get_contents(__DIR__ . '/../json/dataset_show.json'), true);

        $dataset = Dataset::fromArray($data);

        $this->assertSame('staging-vol01/tkyp9wdqip27', $dataset->getId());
        $this->assertSame('staging-vol01/tkyp9wdqip27', $dataset->getName());
        $this->assertSame('staging-vol01', $dataset->getPool());
        $this->assertSame(Dataset::TYPE_FILESYSTEM, $dataset->getType());
        $this->assertSame('/mnt/staging-vol01/tkyp9wdqip27', $dataset->getMountPoint());
    }
}

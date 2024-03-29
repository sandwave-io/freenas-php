<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\FreeNAS\Domain\Pool;

/** @covers \SandwaveIo\FreeNAS\Domain\Pool */
class PoolObjectTest extends TestCase
{
    public function test_create_pool_from_array(): void
    {
        $data = json_decode((string) file_get_contents(__DIR__ . '/../json/pool_index.json'), true);

        $pool = Pool::fromArray($data[0]);

        self::assertInstanceOf(Pool::class, $pool);
        self::assertSame($pool->getName(), 'staging-vol01');
        self::assertSame($pool->getPath(), '/mnt/staging-vol01');
    }
}

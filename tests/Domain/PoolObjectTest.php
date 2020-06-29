<?php

namespace PCextreme\FreeNAS\Tests\Domain;

use PCextreme\FreeNAS\Domain\Pool;
use PHPUnit\Framework\TestCase;

/** @covers \PCextreme\FreeNAS\Domain\Pool */
class PoolObjectTest extends TestCase
{
    public function test_create_pool_from_array()
    {
        $data = json_decode((string) file_get_contents(__DIR__ . '/../json/pool_index.json'), true);

        $pool = Pool::fromArray($data[0]);

        $this->assertInstanceOf(Pool::class, $pool);
    }
}
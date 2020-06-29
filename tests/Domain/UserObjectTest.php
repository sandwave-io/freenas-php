<?php

namespace PCextreme\FreeNAS\Tests\Domain;

use PCextreme\FreeNAS\Domain\User;
use PHPUnit\Framework\TestCase;

/** @covers \PCextreme\FreeNAS\Domain\User */
class UserObjectTest extends TestCase
{
    public function test_create_user_from_array()
    {
        $data = json_decode((string) file_get_contents(__DIR__ . '/../json/user_show.json'), true);

        $user = User::fromArray($data);

        $this->assertSame(42, $user->getId());
        $this->assertSame(1002, $user->getUid());
        $this->assertSame('6qzx5y2pqvvp', $user->getUsername());
        $this->assertSame('/mnt/staging-vol01/6qzx5y2pqvvp', $user->getHomeDir());
        $this->assertSame('6qzx5y2pqvvp', $user->getFullName());
    }
}
<?php

namespace PCextreme\FreeNAS\Tests\Domain;

use PCextreme\FreeNAS\Domain\LifetimeUnit;
use PCextreme\FreeNAS\Domain\Task;
use PHPUnit\Framework\TestCase;

/** @covers \PCextreme\FreeNAS\Domain\Task */
class TaskObjectTest extends TestCase
{
    public function test_create_task_from_array()
    {
        $data = json_decode((string) file_get_contents(__DIR__ . '/../json/task_show.json'), true);

        $task = Task::fromArray($data);

        $this->assertSame(8, $task->getId());
        $this->assertSame('staging-vol01/s2vzaxgxanlv', $task->getDataset());
        $this->assertSame(LifetimeUnit::UNIT_DAY, (string) $task->getLifetimeUnit());
        $this->assertSame(2, $task->getLifetimeValue());
        $this->assertSame(false, $task->isRecursive());
    }
}
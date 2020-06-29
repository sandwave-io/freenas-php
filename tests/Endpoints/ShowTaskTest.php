<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests\Endpoints;

use PCextreme\FreeNAS\Domain\Dataset;
use PCextreme\FreeNAS\Domain\LifetimeUnit;
use PCextreme\FreeNAS\Domain\Task;
use PCextreme\FreeNAS\Exceptions\FreeNasClientException;
use PCextreme\FreeNAS\Exceptions\NotFoundException;
use PCextreme\FreeNAS\Tests\Helpers\MockClientTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** @covers \PCextreme\FreeNAS\RestClient::getSnapshotTask */
class ShowTaskTest extends TestCase
{
    use MockClientTrait;

    public function test_task_endpoint(): void
    {
        $response = (string) file_get_contents(__DIR__ . '/../json/task_show.json');
        $client = $this->getMockedClientWithResponse(200, $response, function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('pool/snapshottask/id/8', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $task = $client->getSnapshotTask(8);
        $this->assertInstanceOf(Task::class, $task);

        $this->assertSame(8, $task->getId());
        $this->assertSame("staging-vol01/s2vzaxgxanlv", $task->getDataset());
        $this->assertSame(2, $task->getLifetimeValue());
        $this->assertTrue($task->getLifetimeUnit()->isEqual(LifetimeUnit::day()));
        $this->assertSame(false, $task->isRecursive());
    }

    public function test_task_endpoint_not_found(): void
    {
        $client = $this->getMockedClientWithResponse(404, "", function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('pool/snapshottask/id/7', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        // This exception should be thrown as the given dataset id is not of the type FILESYSTEM.
        $this->expectException(NotFoundException::class);
        $task = $client->getSnapshotTask(7);
    }

    public function test_task_endpoint_error(): void
    {
        $client = $this->getMockedClientWithResponse(500, '', function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('pool/snapshottask/id/8', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $this->expectException(FreeNasClientException::class);
        $client->getSnapshotTask(8);
    }
}

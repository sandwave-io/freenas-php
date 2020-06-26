<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests\Endpoints;

use PCextreme\FreeNAS\Domain\LifetimeUnit;
use PCextreme\FreeNAS\Domain\Schedule;
use PCextreme\FreeNAS\Domain\Task;
use PCextreme\FreeNAS\Exceptions\FreeNasClientException;
use PCextreme\FreeNAS\Tests\Helpers\MockClientTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** @covers \PCextreme\FreeNAS\RestClient::createSnapshotTask */
class CreateTaskTest extends TestCase
{
    use MockClientTrait;

    public function test_user_endpoint(): void
    {
        $response = (string) file_get_contents(__DIR__ . '/json/task_show.json');
        $client = $this->getMockedClientWithResponse(200, $response, function (RequestInterface $request) {
            $this->assertSame('POST', strtoupper($request->getMethod()));
            $this->assertSame('user', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $task = $client->createSnapshotTask( 'staging-vol01', 'asdfasdf', Schedule::everyDay(),7, LifetimeUnit::day());
        $this->assertInstanceOf(Task::class, $task);
    }

    public function test_user_endpoint_422(): void
    {
        $response = (string) file_get_contents(__DIR__ . '/json/task_create_validation_error.json');
        $client = $this->getMockedClientWithResponse(422, $response, function (RequestInterface $request) {
            $this->assertSame('POST', strtoupper($request->getMethod()));
            $this->assertSame('user', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $this->expectException(FreeNasClientException::class);
        $client->createUser(123001, 'asdfasdf', '/mnt/staging-vol01/asdfasdf', 'secret');
    }
}

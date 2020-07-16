<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Tests\Endpoints;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use SandwaveIo\FreeNAS\Domain\LifetimeUnit;
use SandwaveIo\FreeNAS\Domain\Schedule;
use SandwaveIo\FreeNAS\Domain\Task;
use SandwaveIo\FreeNAS\Exceptions\FreeNasClientException;
use SandwaveIo\FreeNAS\Tests\Helpers\MockClientTrait;

/** @covers \SandwaveIo\FreeNAS\RestClient::createSnapshotTask */
class CreateTaskTest extends TestCase
{
    use MockClientTrait;

    public function test_user_endpoint(): void
    {
        $response = (string) file_get_contents(__DIR__ . '/../json/task_show.json');
        $client = $this->getMockedClientWithResponse(200, $response, function (RequestInterface $request) {
            $this->assertSame('POST', strtoupper($request->getMethod()));
            $this->assertSame('pool/snapshottask', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $task = $client->createSnapshotTask('staging-vol01', 'asdfasdf', Schedule::everyDay(), 7, LifetimeUnit::day());
        $this->assertInstanceOf(Task::class, $task);
    }

    public function test_user_endpoint_422(): void
    {
        $response = (string) file_get_contents(__DIR__ . '/../json/task_create_validation_error.json');
        $client = $this->getMockedClientWithResponse(422, $response, function (RequestInterface $request) {
            $this->assertSame('POST', strtoupper($request->getMethod()));
            $this->assertSame('pool/snapshottask', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $this->expectException(FreeNasClientException::class);
        $client->createUser(123001, 'asdfasdf', '/mnt/staging-vol01/asdfasdf', 'secret');
    }
}

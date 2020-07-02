<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests\Endpoints;

use PCextreme\FreeNAS\Exceptions\FreeNasClientException;
use PCextreme\FreeNAS\Tests\Helpers\MockClientTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** @covers \PCextreme\FreeNAS\RestClient::deleteSnapshotTask */
class DeleteTaskTest extends TestCase
{
    use MockClientTrait;

    public function test_user_endpoint(): void
    {
        $client = $this->getMockedClientWithResponse(200, '', function (RequestInterface $request) {
            $this->assertSame('DELETE', strtoupper($request->getMethod()));
            $this->assertSame('pool/snapshottask/id/8', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $client->deleteSnapshotTask(8);
    }

    public function test_user_endpoint_500(): void
    {
        $client = $this->getMockedClientWithResponse(500, '', function (RequestInterface $request) {
            $this->assertSame('DELETE', strtoupper($request->getMethod()));
            $this->assertSame('pool/snapshottask/id/8', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $this->expectException(FreeNasClientException::class);
        $client->deleteSnapshotTask(8);
    }
}

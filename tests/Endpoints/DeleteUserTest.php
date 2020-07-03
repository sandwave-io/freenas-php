<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Tests\Endpoints;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use SandwaveIo\FreeNAS\Exceptions\FreeNasClientException;
use SandwaveIo\FreeNAS\Tests\Helpers\MockClientTrait;

/** @covers \SandwaveIo\FreeNAS\RestClient::deleteUser */
class DeleteUserTest extends TestCase
{
    use MockClientTrait;

    public function test_user_endpoint(): void
    {
        $client = $this->getMockedClientWithResponse(200, '', function (RequestInterface $request) {
            $this->assertSame('DELETE', strtoupper($request->getMethod()));
            $this->assertSame('user/id/42', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $client->deleteUser(42);
    }

    public function test_user_endpoint_500(): void
    {
        $client = $this->getMockedClientWithResponse(500, '', function (RequestInterface $request) {
            $this->assertSame('DELETE', strtoupper($request->getMethod()));
            $this->assertSame('user/id/42', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $this->expectException(FreeNasClientException::class);
        $client->deleteUser(42);
    }
}

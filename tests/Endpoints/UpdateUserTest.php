<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests\Endpoints;

use PCextreme\FreeNAS\Domain\User;
use PCextreme\FreeNAS\Exceptions\FreeNasClientException;
use PCextreme\FreeNAS\Tests\Helpers\MockClientTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class UpdateUserTest extends TestCase
{
    use MockClientTrait;

    public function test_user_endpoint(): void
    {
        $client = $this->getMockedClientWithResponse(200, '', function (RequestInterface $request) {
            $this->assertSame('PUT', strtoupper($request->getMethod()));
            $this->assertSame('user/id/42', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $client->updateUserPassword(42, 'Admin123');
    }

    public function test_user_endpoint_500(): void
    {
        $client = $this->getMockedClientWithResponse(500, '', function (RequestInterface $request) {
            $this->assertSame('PUT', strtoupper($request->getMethod()));
            $this->assertSame('user/id/42', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $this->expectException(FreeNasClientException::class);
        $client->updateUserPassword(42, 'Admin123');
    }
}

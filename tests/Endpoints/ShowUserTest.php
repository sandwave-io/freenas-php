<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests\Endpoints;

use PCextreme\FreeNAS\Domain\User;
use PCextreme\FreeNAS\Exceptions\FreeNasClientException;
use PCextreme\FreeNAS\Tests\Helpers\MockClientTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** @covers \PCextreme\FreeNAS\RestClient::getUser */
class ShowUserTest extends TestCase
{
    use MockClientTrait;

    public function test_user_endpoint(): void
    {
        $response = (string) file_get_contents(__DIR__ . '/../json/user_show.json');
        $client = $this->getMockedClientWithResponse(200, $response, function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('user/id/42', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $user = $client->getUser(42);
        $this->assertInstanceOf(User::class, $user);

        $this->assertSame(42, $user->getId());
        $this->assertSame(1002, $user->getUid());
        $this->assertSame('6qzx5y2pqvvp', $user->getUsername());
        $this->assertSame('6qzx5y2pqvvp', $user->getFullName());
        $this->assertSame('/mnt/staging-vol01/6qzx5y2pqvvp', $user->getHomeDir());
    }

    public function test_user_endpoint_404(): void
    {
        $client = $this->getMockedClientWithResponse(404, '', function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('user/id/42', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $this->expectException(FreeNasClientException::class);
        $client->getUser(42);
    }
}

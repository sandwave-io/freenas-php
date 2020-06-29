<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests\Endpoints;

use PCextreme\FreeNAS\Exceptions\FreeNasClientException;
use PCextreme\FreeNAS\Tests\Helpers\MockClientTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** @covers \PCextreme\FreeNAS\RestClient::createUser */
class CreateUserTest extends TestCase
{
    use MockClientTrait;

    public function test_user_endpoint(): void
    {
        $client = $this->getMockedClientWithResponse(200, '123', function (RequestInterface $request) {
            $this->assertSame('POST', strtoupper($request->getMethod()));
            $this->assertSame('user', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $userId = $client->createUser(123001, 'asdfasdf', '/mnt/staging-vol01/asdfasdf', 'secret');
        $this->assertIsInt($userId);
    }

    public function test_user_endpoint_422(): void
    {
        $response = (string) file_get_contents(__DIR__ . '/../json/user_create_validation_error.json');
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

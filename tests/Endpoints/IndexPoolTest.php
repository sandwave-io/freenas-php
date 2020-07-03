<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Tests\Endpoints;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use SandwaveIo\FreeNAS\Domain\Pool;
use SandwaveIo\FreeNAS\Exceptions\FreeNasClientException;
use SandwaveIo\FreeNAS\Exceptions\NotFoundException;
use SandwaveIo\FreeNAS\Tests\Helpers\MockClientTrait;

/** @covers \SandwaveIo\FreeNAS\RestClient::getPool */
class IndexPoolTest extends TestCase
{
    use MockClientTrait;

    public function test_pool_endpoint(): void
    {
        $response = (string) file_get_contents(__DIR__ . '/../json/pool_index.json');
        $client = $this->getMockedClientWithResponse(200, $response, function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('pool', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $pool = $client->getPool('staging-vol01');
        $this->assertInstanceOf(Pool::class, $pool);
    }

    public function test_pool_endpoint_wrong_name(): void
    {
        $response = (string) file_get_contents(__DIR__ . '/../json/pool_index.json');
        $client = $this->getMockedClientWithResponse(200, $response, function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('pool', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $this->expectException(NotFoundException::class);
        $client->getPool('staging-vol02');
    }

    public function test_pool_endpoint_internal_error(): void
    {
        $response = 'Oops something went horribly wrong.';
        $client = $this->getMockedClientWithResponse(500, $response, function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('pool', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $this->expectException(FreeNasClientException::class);
        $this->expectExceptionMessage('Unexpected response (got 500, expected 200). Body: Oops something went horribly wrong.');
        $client->getPool('staging-vol01');
    }
}

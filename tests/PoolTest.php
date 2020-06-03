<?php

namespace PCextreme\FreeNAS\Tests;

use PCextreme\FreeNAS\Domain\Pool;
use PCextreme\FreeNAS\Exceptions\FreeNasClientException;
use PCextreme\FreeNAS\Exceptions\NotFoundException;
use PCextreme\FreeNAS\Tests\Helpers\MockClientTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class PoolTest extends TestCase
{
    use MockClientTrait;

    public function test_pool_endpoint()
    {
        $response = file_get_contents(__DIR__ . '/json/pool_index.json');
        $client = $this->getMockedClientWithResponse(200, $response, function (RequestInterface $request) {
            $this->assertEquals('GET', strtoupper($request->getMethod()));
            $this->assertEquals('pool', $request->getUri()->getPath());
            $this->assertEquals('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $pool = $client->getPool('staging-vol01');
        $this->assertInstanceOf(Pool::class, $pool);
    }

    public function test_pool_endpoint_wrong_name()
    {
        $response = file_get_contents(__DIR__ . '/json/pool_index.json');
        $client = $this->getMockedClientWithResponse(200, $response, function (RequestInterface $request) {
            $this->assertEquals('GET', strtoupper($request->getMethod()));
            $this->assertEquals('pool', $request->getUri()->getPath());
            $this->assertEquals('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $this->expectException(NotFoundException::class);
        $client->getPool('staging-vol02');
    }

    public function test_pool_endpoint_internal_error()
    {
        $response = "Oops something went horribly wrong.";
        $client = $this->getMockedClientWithResponse(500, $response, function (RequestInterface $request) {
            $this->assertEquals('GET', strtoupper($request->getMethod()));
            $this->assertEquals('pool', $request->getUri()->getPath());
            $this->assertEquals('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $this->expectException(FreeNasClientException::class);
        $this->expectExceptionMessage('Unexpected response (got 500, expected 200). Body: Oops something went horribly wrong.');
        $client->getPool('staging-vol01');
    }
}

<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PCextreme\FreeNAS\Exceptions\FreeNasClientException;
use PCextreme\FreeNAS\Exceptions\NotFoundException;
use PCextreme\FreeNAS\Support\BasicAuthClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** @covers \PCextreme\FreeNAS\Support\BasicAuthClient */
class BasicAuthClientTest extends TestCase
{
    public function test_construct(): void
    {
        $client = new BasicAuthClient(
            'https://example.com/api/v2/',
            'root',
            'adminadmin'
        );
        $this->assertInstanceOf(BasicAuthClient::class, $client);
    }

    public function requestVariants(): array
    {
        return [
            'GET request: success'  => ['get', 200, null],
            'GET request: not found'  => ['get', 404, NotFoundException::class],
            'GET request: error'  => ['get', 500, FreeNasClientException::class],
            'POST request: success'  => ['post', 201, null],
            'POST request: not found'  => ['post', 404, NotFoundException::class],
            'POST request: error'  => ['post', 500, FreeNasClientException::class],
            'PUT request: success'  => ['put', 200, null],
            'PUT request: not found'  => ['put', 404, NotFoundException::class],
            'PUT request: error'  => ['put', 500, FreeNasClientException::class],
            'DELETE request: success'  => ['delete', 200, null],
            'DELETE request: not found'  => ['delete', 404, NotFoundException::class],
            'DELETE request: error'  => ['delete', 500, FreeNasClientException::class],
        ];
    }

    /** @dataProvider requestVariants */
    public function test_http_methods(string $method, int $response, ?string $exception): void
    {
        $client = $this->getMockedClient($response, '', function (RequestInterface $request) use ($method) {
            $this->assertSame(strtoupper($method), strtoupper($request->getMethod()));
            $this->assertSame('test', $request->getUri()->getPath());
            $this->assertSame('Basic dGVzdDphZG1pbmFkbWlu', $request->getHeader('Authorization')[0]);
        });

        if ($exception) {
            $this->expectException($exception);
        }
        if ($method === 'post') {
            $client->{$method}('test', ['foo' => 'bar']);
        } else {
            $client->{$method}('test');
        }
    }

    private function getMockedClient(int $responseCode, string $responseBody, ?callable $assertClosure = null): BasicAuthClient
    {
        $fakeClient = new BasicAuthClient('example.com', 'test', 'adminadmin');

        $handlerStack = HandlerStack::create(new MockHandler([
            new Response($responseCode, [], $responseBody),
        ]));

        if ($assertClosure !== null) {
            $handlerStack->push(function (callable $handler) use ($assertClosure) {
                return function (RequestInterface $request, $options) use ($handler, $assertClosure) {
                    $assertClosure($request);
                    return $handler($request, $options);
                };
            });
        }

        $fakeClient->setClient(new Client(['handler' => $handlerStack]));

        return $fakeClient;
    }
}

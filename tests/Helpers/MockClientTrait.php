<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Tests\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use SandwaveIo\FreeNAS\RestClient;
use SandwaveIo\FreeNAS\Support\BasicAuthClient;

trait MockClientTrait
{
    public function getMockedClientWithResponse(int $responseCode, string $responseBody, ?callable $assertClosure = null): RestClient
    {
        $service = new RestClient('example.com', 'test', 'adminadmin');
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
        $service->setClient($fakeClient);

        return $service;
    }
}

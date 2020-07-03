<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Tests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\FreeNAS\RestClient;
use SandwaveIo\FreeNAS\Support\BasicAuthClient;

/** @covers \SandwaveIo\FreeNAS\RestClient */
class RestClientTest extends TestCase
{
    public function test_construct(): void
    {
        $client = new RestClient(
            'https://example.com/api/v2/',
            'root',
            'adminadmin'
        );

        $client->setClient(new BasicAuthClient(
            'https://example.com/api/v2/',
            'root',
            'adminadmin'
        ));

        $this->assertInstanceOf(RestClient::class, $client);
    }
}

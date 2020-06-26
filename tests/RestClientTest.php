<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests;

use PCextreme\FreeNAS\RestClient;
use PCextreme\FreeNAS\Support\BasicAuthClient;
use PHPUnit\Framework\TestCase;

/** @covers \PCextreme\FreeNAS\RestClient */
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

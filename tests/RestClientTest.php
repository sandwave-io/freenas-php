<?php

namespace PCextreme\FreeNAS\Tests;

use PCextreme\FreeNAS\RestClient;
use PHPUnit\Framework\TestCase;

class RestClientTest extends TestCase
{
    public function test_construct()
    {
        $client = new RestClient(
            "https://example.com/api/v2/",
            "root",
            "adminadmin"
        );
        $this->assertInstanceOf(RestClient::class, $client);
    }
}

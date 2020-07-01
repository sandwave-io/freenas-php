<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests;

use PCextreme\FreeNAS\Exceptions\UnexpectedValueException;
use PCextreme\FreeNAS\Support\FreeNasResponse;
use PHPUnit\Framework\TestCase;

/** @covers \PCextreme\FreeNAS\Support\FreeNasResponse */
class ResponseObjectTest extends TestCase
{
    public function test_get_text()
    {
        $response = FreeNasResponse::fromString('This is text');

        $this->assertSame('This is text', $response->text());
    }

    public function test_to_string()
    {
        $response = FreeNasResponse::fromString('This is text');

        $this->assertSame('This is text', (string) $response);
    }

    public function test_parse_json()
    {
        $response = FreeNasResponse::fromString('{"foo": "bar"}');

        $this->assertSame(['foo' => 'bar'], $response->json());
    }

    public function test_invalid_json()
    {
        $response = FreeNasResponse::fromString('{"foo":  <><>>>>><<<< {{{{{{{{{{{{) "bar"}');

        $this->expectException(UnexpectedValueException::class);
        $response->json();
    }
}

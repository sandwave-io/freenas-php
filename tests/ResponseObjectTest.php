<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Tests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\FreeNAS\Exceptions\UnexpectedValueException;
use SandwaveIo\FreeNAS\Support\FreeNasResponse;

/** @covers \SandwaveIo\FreeNAS\Support\FreeNasResponse */
class ResponseObjectTest extends TestCase
{
    public function test_get_text(): void
    {
        $response = FreeNasResponse::fromString('This is text');

        $this->assertSame('This is text', $response->text());
    }

    public function test_to_string(): void
    {
        $response = FreeNasResponse::fromString('This is text');

        $this->assertSame('This is text', (string) $response);
    }

    public function test_parse_json(): void
    {
        $response = FreeNasResponse::fromString('{"foo": "bar"}');

        $this->assertSame(['foo' => 'bar'], $response->json());
    }

    public function test_invalid_json(): void
    {
        $response = FreeNasResponse::fromString('{"foo":  <><>>>>><<<< {{{{{{{{{{{{) "bar"}');

        $this->expectException(UnexpectedValueException::class);
        $response->json();
    }
}

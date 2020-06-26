<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests\Endpoints;

use PCextreme\FreeNAS\Domain\Dataset;
use PCextreme\FreeNAS\Exceptions\FreeNasClientException;
use PCextreme\FreeNAS\Tests\Helpers\MockClientTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** @covers \PCextreme\FreeNAS\RestClient::createDataset */
class CreateDatasetTest extends TestCase
{
    use MockClientTrait;

    public function test_user_endpoint(): void
    {
        $response = (string) file_get_contents(__DIR__ . '/json/dataset_show.json');
        $client = $this->getMockedClientWithResponse(200, $response, function (RequestInterface $request) {
            $this->assertSame('POST', strtoupper($request->getMethod()));
            $this->assertSame('pool/dataset', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $task = $client->createDataset( 'staging-vol01', 'asdfasdf', 1024 ** 3 /* 1MB */);
        $this->assertInstanceOf(Dataset::class, $task);
    }

    public function test_user_endpoint_422(): void
    {
        $response = (string) file_get_contents(__DIR__ . '/json/dataset_create_validation_error.json');
        $client = $this->getMockedClientWithResponse(422, $response, function (RequestInterface $request) {
            $this->assertSame('POST', strtoupper($request->getMethod()));
            $this->assertSame('pool/dataset', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $this->expectException(FreeNasClientException::class);
        $task = $client->createDataset( 'staging-vol01', 'asdfasdf', 1024 ** 3 /* 1MB */);
    }
}

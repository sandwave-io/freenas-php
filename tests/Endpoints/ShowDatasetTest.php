<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests\Endpoints;

use PCextreme\FreeNAS\Domain\Dataset;
use PCextreme\FreeNAS\Exceptions\FreeNasClientException;
use PCextreme\FreeNAS\Exceptions\NotFoundException;
use PCextreme\FreeNAS\Tests\Helpers\MockClientTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** @covers \PCextreme\FreeNAS\RestClient::getDataset */
class ShowDatasetTest extends TestCase
{
    use MockClientTrait;

    public function test_dataset_endpoint(): void
    {
        $response = (string) file_get_contents(__DIR__ . '/../json/dataset_show.json');
        $client = $this->getMockedClientWithResponse(200, $response, function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('pool/dataset/id/staging-vol01%2Ftkyp9wdqip27', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $dataset = $client->getDataset('staging-vol01', 'tkyp9wdqip27');
        $this->assertInstanceOf(Dataset::class, $dataset);

        $this->assertSame('staging-vol01/tkyp9wdqip27', $dataset->getName());
        $this->assertSame('staging-vol01', $dataset->getPool());
        $this->assertSame(Dataset::TYPE_FILESYSTEM, $dataset->getType());
        $this->assertSame('/mnt/staging-vol01/tkyp9wdqip27', $dataset->getMountPoint());
    }

    public function test_dataset_endpoint_not_found(): void
    {
        $client = $this->getMockedClientWithResponse(404, '', function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('pool/dataset/id/staging-vol01%2Fasdfasdfasdf', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        // This exception should be thrown as the given dataset id is not of the type FILESYSTEM.
        $this->expectException(NotFoundException::class);
        $dataset = $client->getDataset('staging-vol01', 'asdfasdfasdf');
    }

    public function test_dataset_endpoint_error(): void
    {
        $client = $this->getMockedClientWithResponse(500, '', function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('pool/dataset/id/staging-vol01%2Fasdfasdfasdf', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        // This exception should be thrown as the given dataset id is not of the type FILESYSTEM.
        $this->expectException(FreeNasClientException::class);
        $client->getDataset('staging-vol01', 'asdfasdfasdf');
    }
}

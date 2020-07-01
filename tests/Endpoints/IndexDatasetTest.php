<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests\Endpoints;

use PCextreme\FreeNAS\Domain\Dataset;
use PCextreme\FreeNAS\Domain\DatasetCollection;
use PCextreme\FreeNAS\Exceptions\FreeNasClientException;
use PCextreme\FreeNAS\Exceptions\NotFoundException;
use PCextreme\FreeNAS\Tests\Helpers\MockClientTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** @covers \PCextreme\FreeNAS\RestClient::getDatasets */
class IndexDatasetTest extends TestCase
{
    use MockClientTrait;

    public function test_dataset_endpoint(): void
    {
        $response = (string) file_get_contents(__DIR__ . '/../json/dataset_index.json');
        $client = $this->getMockedClientWithResponse(200, $response, function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('pool/dataset/id/staging-vol01', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $datasets = $client->getDatasets('staging-vol01');
        $this->assertInstanceOf(DatasetCollection::class, $datasets);
        assert($datasets[0] !== null);
        $dataset = $datasets[0];
        $this->assertInstanceOf(Dataset::class, $dataset);

        $this->assertSame('staging-vol01/mj4io2xak1ga', $dataset->getName());
        $this->assertSame('staging-vol01', $dataset->getPool());
        $this->assertSame(Dataset::TYPE_FILESYSTEM, $dataset->getType());
        $this->assertSame('/mnt/staging-vol01/mj4io2xak1ga', $dataset->getMountPoint());
    }

    public function test_dataset_endpoint_not_found(): void
    {
        $client = $this->getMockedClientWithResponse(404, '', function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('pool/dataset/id/staging-vol01', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        // This exception should be thrown as the given dataset id is not of the type FILESYSTEM.
        $this->expectException(NotFoundException::class);
        $dataset = $client->getDatasets('staging-vol01');
    }

    public function test_dataset_endpoint_error(): void
    {
        $client = $this->getMockedClientWithResponse(500, '', function (RequestInterface $request) {
            $this->assertSame('GET', strtoupper($request->getMethod()));
            $this->assertSame('pool/dataset/id/staging-vol01', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        // This exception should be thrown as the given dataset id is not of the type FILESYSTEM.
        $this->expectException(FreeNasClientException::class);
        $client->getDatasets('staging-vol01');
    }
}

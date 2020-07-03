<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Tests\Endpoints;

use PCextreme\FreeNAS\Exceptions\FreeNasClientException;
use PCextreme\FreeNAS\Tests\Helpers\MockClientTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** @covers \PCextreme\FreeNAS\RestClient::resizeDataset */
class ResizeDatasetTest extends TestCase
{
    use MockClientTrait;

    public function test_user_endpoint(): void
    {
        $client = $this->getMockedClientWithResponse(200, '', function (RequestInterface $request) {
            $this->assertSame('PUT', strtoupper($request->getMethod()));
            $this->assertSame('pool/dataset/id/staging-vol01%2Ftkyp9wdqip27', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $client->resizeDataset('staging-vol01', 'tkyp9wdqip27', 20 * 1024**3);
    }

    public function test_user_endpoint_500(): void
    {
        $client = $this->getMockedClientWithResponse(500, '', function (RequestInterface $request) {
            $this->assertSame('PUT', strtoupper($request->getMethod()));
            $this->assertSame('pool/dataset/id/staging-vol01%2Ftkyp9wdqip27', $request->getUri()->getPath());
            $this->assertSame('', $request->getUri()->getQuery());
            $this->assertNotEmpty($request->getHeader('Authorization'));
        });

        $this->expectException(FreeNasClientException::class);
        $client->resizeDataset('staging-vol01', 'tkyp9wdqip27', 20 * 1024**3);
    }
}
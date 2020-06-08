<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Support;

use GuzzleHttp\Client;
use PCextreme\FreeNAS\Exceptions\FreeNasClientException;
use Psr\Http\Message\ResponseInterface;

class BasicAuthClient
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var Client */
    private $client;

    public function __construct(string $baseUrl, string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;

        $this->client = new Client([
            'base_uri' => $baseUrl,
        ]);
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function get(string $endpoint, array $query = [], int $expectedResponse = 200): FreeNasResponse
    {
        return $this->request('GET', $endpoint, [], $query, $expectedResponse);
    }

    public function post(string $endpoint, array $body = [], array $query = [], int $expectedResponse = 201): FreeNasResponse
    {
        return $this->request('POST', $endpoint, $body, $query, $expectedResponse);
    }

    public function put(string $endpoint, array $body = [], array $query = [], int $expectedResponse = 200): FreeNasResponse
    {
        return $this->request('PUT', $endpoint, $body, $query, $expectedResponse);
    }

    public function delete(string $endpoint, array $query = [], int $expectedResponse = 200): FreeNasResponse
    {
        return $this->request('DELETE', $endpoint, [], $query, $expectedResponse);
    }

    private function request(string $method, string $endpoint, array $body = [], array $query = [], int $expectedResponse = 200): FreeNasResponse
    {
        $metaData = [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password),
            ],
            'http_errors' => false,
        ];

        if ($body !== []) {
            $metaData['json'] = $body;
        }

        $response = $this->client->request($method, $endpoint . $this->buildQuery($query), $metaData);

        return $this->handleResponse($response, $expectedResponse);
    }

    private function handleResponse(ResponseInterface $response, int $expectedResponse): FreeNasResponse
    {
        if ($response->getStatusCode() !== $expectedResponse) {
            throw new FreeNasClientException("Unexpected response (got {$response->getStatusCode()}, expected {$expectedResponse}). Body: " . $response->getBody());
        }

        return FreeNasResponse::fromString((string) $response->getBody());
    }

    private function buildQuery(array $parameters): string
    {
        return ($parameters === []) ? '' : ('?' . http_build_query($parameters));
    }
}

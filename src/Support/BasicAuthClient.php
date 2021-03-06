<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Support;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use SandwaveIo\FreeNAS\Exceptions\FreeNasClientException;
use SandwaveIo\FreeNAS\Exceptions\NotFoundException;

class BasicAuthClient
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var Client */
    private $client;

    public function __construct(string $baseUrl, string $username, string $password, array $guzzleOptions = [])
    {
        $this->username = $username;
        $this->password = $password;

        $this->client = new Client(array_merge($guzzleOptions, [
            'base_uri' => $baseUrl,
        ]));
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
        if ($response->getStatusCode() === $expectedResponse) {
            return FreeNasResponse::fromString((string) $response->getBody());
        } elseif ($response->getStatusCode() === 404) {
            throw new NotFoundException('Not found.');
        }
        throw new FreeNasClientException("Unexpected response (got {$response->getStatusCode()}, expected {$expectedResponse}). Body: " . $response->getBody());
    }

    private function buildQuery(array $parameters): string
    {
        return ($parameters === []) ? '' : ('?' . http_build_query($parameters));
    }
}

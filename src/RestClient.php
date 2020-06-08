<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS;

use PCextreme\FreeNAS\Domain\Dataset;
use PCextreme\FreeNAS\Domain\DatasetCollection;
use PCextreme\FreeNAS\Domain\Pool;
use PCextreme\FreeNAS\Domain\User;
use PCextreme\FreeNAS\Exceptions\NotFoundException;
use PCextreme\FreeNAS\Support\BasicAuthClient;

final class RestClient
{
    /** @var BasicAuthClient */
    private $client;

    public function __construct(string $baseUrl, string $username, string $password)
    {
        $this->client = new BasicAuthClient($baseUrl, $username, $password);
    }

    public function setClient(BasicAuthClient $client): void
    {
        $this->client = $client;
    }

    public function getPool(string $name): Pool
    {
        $pools = $this->client->get('pool')->json();
        foreach ($pools as $pool) {
            if (is_array($pool) && isset($pool['name'])) {
                if ($pool['name'] === $name) {
                    return Pool::fromArray($pool);
                }
            }
        }

        throw new NotFoundException("Could not resolve pool [$name]");
    }

    public function createDataset(string $volume, string $name, int $size, ?string $comment = null): Dataset
    {
        $response = $this->client->post('pool/dataset', [
            'name' => $name,
            'type' => 'VOLUME',
            'volsize' => $size,
            'comments' => $comment ?? '',
        ], [], 200)->json();

        return Dataset::fromArray($response);
    }

    public function getDataset(string $volume, string $datasetId): Dataset
    {
        $collection = DatasetCollection::fromArray($this->client->get("pool/dataset/id/{$volume}")->json());
        return $collection->getDataset("{$volume}/{$datasetId}");
    }


    public function createUser(
        int $uid,
        string $name,
        string $home,
        string $password,
        bool $createGroup = true
    ): int {
        $response = $this->client->post('user', [
            'uid'          => $uid,
            'username'     => $name,
            'home'         => $home,
            'full_name'    => $name,
            'password'     => $password,
            'group_create' => $createGroup,
        ], [], 200)->text();

        return (int) $response;
    }

    public function getUser(int $userId): User
    {
        $user = $this->client->get("user/id/{$userId}")->json();
        return User::fromArray($user);
    }

    public function updateUserPassword(int $userId, string $password): void
    {
        $this->client->put("user/id/{$userId}", [
            'password' => $password,
        ]);
    }

    public function deleteUser(int $userId): void
    {
        $this->client->delete("user/id/{$userId}");
    }
}

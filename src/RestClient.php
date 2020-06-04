<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS;

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

    public function createDataset(string $datasetId, int $size, ?string $comment = null)
    {
        // TODO: Implement.
        // name -> datasetId
        // type -> 'VOLUME'
        // volsize & quota -> size
        // if $comment: comments -> $comment. else skip.
        // POST: /pool/dataset
    }

    public function getDataset(string $datasetId)
    {
        // TODO: Implement.
        // GET: /pool/dataset/id/{id}
    }

    public function updateDatasetQuota(string $datasetId, int $size)
    {
        // TODO: Implement.
        // update volsize and quota.
        // PUT: /pool/dataset/id/{id}
    }

    public function createTask(string $datasetId, int $intervalDays, int $lifetimeDays)
    {
        // TODO: Implement.
        // POST: /pool/snapshottask
    }

    public function deleteTask(string $taskId)
    {
        // TODO: Implement.
        // DELETE: /pool/snapshottask/id/{id}
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

    public function getUser(int $userId)
    {
        $user = $this->client->get("user/id/{$userId}")->json();
        return User::fromArray($user);
    }

    public function updateUser(string $userId, string $password)
    {
        // TODO: Implement.
        // password
        // UPDATE: /user/id/{id}
    }

    public function deleteUser(string $userId)
    {
        // TODO: Implement.
        // DELETE: /user/id/{id}
    }
}

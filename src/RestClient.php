<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS;

use SandwaveIo\FreeNAS\Domain\Dataset;
use SandwaveIo\FreeNAS\Domain\DatasetCollection;
use SandwaveIo\FreeNAS\Domain\LifetimeUnit;
use SandwaveIo\FreeNAS\Domain\Pool;
use SandwaveIo\FreeNAS\Domain\Schedule;
use SandwaveIo\FreeNAS\Domain\Task;
use SandwaveIo\FreeNAS\Domain\TaskCollection;
use SandwaveIo\FreeNAS\Domain\User;
use SandwaveIo\FreeNAS\Exceptions\NotFoundException;
use SandwaveIo\FreeNAS\Support\BasicAuthClient;

final class RestClient
{
    /** @var BasicAuthClient */
    private $client;

    public function __construct(string $baseUrl, string $username, string $password, array $guzzleOptions = [])
    {
        $this->client = new BasicAuthClient($baseUrl, $username, $password, $guzzleOptions);
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

    public function createDataset(string $volume, string $name, int $size, ?string $comment = null, string $type = Dataset::TYPE_VOLUME): Dataset
    {
        $response = $this->client->post('pool/dataset', [
            'name' => $name,
            'type' => Dataset::TYPE_VOLUME,
            'volsize' => $size,
            'comments' => $comment ?? '',
        ], [], 200)->json();

        return Dataset::fromArray($response);
    }

    public function getDatasets(string $volume): DatasetCollection
    {
        return DatasetCollection::fromArray($this->client->get("pool/dataset/id/{$volume}")->json());
    }

    public function getDataset(string $volume, string $datasetId): Dataset
    {
        $path = urlencode("{$volume}/{$datasetId}");
        return Dataset::fromArray($this->client->get("pool/dataset/id/{$path}")->json());
    }

    public function resizeDataset(string $volume, string $datasetId, int $size): void
    {
        $path = urlencode("{$volume}/{$datasetId}");
        $this->client->put("pool/dataset/id/{$path}", [
            'volsize' => $size,
        ]);
    }

    public function deleteDataset(string $volume, string $datasetId): void
    {
        $path = urlencode("{$volume}/{$datasetId}");
        $this->client->delete("pool/dataset/id/{$path}");
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

        // The response given is an ID, we cast this to an integer.
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

    public function getSnapshotTasks(): TaskCollection
    {
        return TaskCollection::fromArray($this->client->get('pool/snapshottask')->json());
    }

    public function getSnapshotTask(int $taskId): Task
    {
        return Task::fromArray($this->client->get("pool/snapshottask/id/{$taskId}")->json());
    }

    public function createSnapshotTask(string $volume, string $datasetId, Schedule $schedule, int $lifetimeValue, LifetimeUnit $lifetimeUnit, string $namingSchema = '%Y_%m_%d_%H_%M'): Task
    {
        $path = urlencode("{$volume}/{$datasetId}");
        $response = $this->client->post('user', [
            'dataset' => $path,
            'recursive' => false,
            'naming_schema' => $namingSchema,
            'lifetime_value' => $lifetimeValue,
            'lifetime_unit' => (string) $lifetimeUnit,
            'schedule' => $schedule->toArray(),
        ], [], 200);

        return Task::fromArray($response->json());
    }

    public function deleteSnapshotTask(int $taskId): void
    {
        $this->client->delete("pool/snapshottask/id/{$taskId}");
    }
}

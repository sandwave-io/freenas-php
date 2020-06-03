<?php

namespace PCextreme\FreeNAS;

final class RestClient
{
    /** @var string */
    private $baseUrl;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    public function __construct(string $baseUrl, string $username, string $password)
    {
        $this->baseUrl  = $baseUrl;
        $this->username = $username;
        $this->password = $password;
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
        string $username,
        string $home,
        string $fullName,
        string $password,
        bool $createGroup = true
    ) {
        // TODO: Implement.
        // uid, username, group_create, home, full_name, password
        // POST: /user
    }

    public function getUser(string $userId)
    {
        // TODO: Implement.
        // GET: /user/id/{id}
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

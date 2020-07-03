<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Domain;

class User
{
    /** @var int */
    private $id;

    /** @var int */
    private $uid;

    /** @var string */
    private $username;

    /** @var string */
    private $home;

    /** @var string */
    private $fullName;

    public function __construct(int $id, int $uid, string $username, string $home, string $fullName)
    {
        $this->id       = $id;
        $this->uid      = $uid;
        $this->username = $username;
        $this->home     = $home;
        $this->fullName = $fullName;
    }

    public static function fromArray(array $data): User
    {
        return new User(
            $data['id'],
            $data['uid'],
            $data['username'],
            $data['home'],
            $data['full_name']
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUid(): int
    {
        return $this->uid;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getHomeDir(): string
    {
        return $this->home;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }
}

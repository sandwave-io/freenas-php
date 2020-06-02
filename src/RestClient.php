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
}

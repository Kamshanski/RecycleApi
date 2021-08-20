<?php

class User {
    public $login = "";
    public $userId = 0;
    public $hash = "";
    public $mac = "";

    /**
     * @param string $login
     * @param int $userId
     * @param string $hash
     * @param string $mac
     */
    public function __construct(string $login, int $userId, string $hash, string $mac)
    {
        $this->login = $login;
        $this->userId = $userId;
        $this->hash = $hash;
        $this->mac = $mac;
    }


}
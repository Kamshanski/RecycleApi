<?php

include_once __DIR__ . "/BaseResponse.php";

class LoginResponse extends BaseResponse {
    protected $login;

    /**
     * @param ?string $login
     */
    public function __construct(?string $login) {
        $this->login = $login ?? "";
    }

}
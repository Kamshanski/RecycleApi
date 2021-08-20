<?php

include_once __DIR__ . "/_main.php";
include_once __DIR__ . "/utils/utils.php";
includeOnceAll(__DIR__ . "/model");

class RegistrationResponse extends LoginResponse {
    public $userId = 0;
}

class RegistrationProcessor extends PostProcessor {
    private $login = "";

    /*** @throws Exception */
    public function processPost(Repository $repository, array $payload): BaseResponse {
        $this->login = $payload["login"];
        $password = $payload["password"];
        $mac = $payload["mac"];

        requireAllNonNullOrBlank("Cannot be empty: ", [
            "login" => $this->login,
            "password" => $password,
            "mac" => $mac,
        ]);

        $user = $repository->addNewUser($this->login, $password, $mac);

        $response = new RegistrationResponse($this->login);

        if ($user) {
            $response->userId = $user->userId;
        }

        return $response;
    }

    protected function getEmptyResponse(): BaseResponse {
        return new RegistrationResponse($this->login);
    }
}

if (isEmpty(debug_backtrace())) {
    main(["POST" => new RegistrationProcessor()]);
}

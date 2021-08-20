<?php

include_once __DIR__ . "/_main.php";
include_once __DIR__ . "/utils/utils.php";
includeOnceAll(__DIR__ . "/model");

class UserExistsResponse extends BaseResponse {
    protected $login = "";
    public $exists = false;

    /**
     * @param string $login
     */
    public function __construct(string $login) {
        $this->login = $login;
    }
}

class UserExistsProcessor extends PostProcessor {
    /** @throws Exception */
    public function processPost(Repository $repository, array $payload): BaseResponse {
        $login = $payload["login"];

        requireAllNonNullOrBlank("Cannot be empty: ", ["login" => $login]);

        $response = new UserExistsResponse($login);

        $response->exists = $repository->checkUserExists($payload["login"]);
        return $response;
    }
}

if (isEmpty(debug_backtrace())) {
    main(["POST" => new UserExistsProcessor()]);
}




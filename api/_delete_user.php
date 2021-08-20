<?php

include_once __DIR__ . "/_main.php";
include_once __DIR__ . "/utils/utils.php";
includeOnceAll(__DIR__ . "/model");

class DeleteUserResponse extends BaseResponse {
    protected $login = "";
    public $deleted = false;

    /**
     * @param string $login
     */
    public function __construct(string $login) {
        $this->login = $login;
    }
}

class DeleteUserProcessor extends PostProcessor {
    /** @throws Exception */
    public function processPost(Repository $repository, array $payload): BaseResponse {
        $payload = $this->getPostContent();
        $login = $payload["login"];
        $userId = $payload["userId"];

        requireAllNonNullOrBlank("Cannot be empty: ", [
            "login" => $login,
            "userId" => $userId
        ]);

        $response = new DeleteUserResponse($login);

        $response->deleted = $repository->deleteUser($login, intval($userId));
        return $response;
    }
}

if (isEmpty(debug_backtrace())) {
    main(["POST" => new DeleteUserProcessor()]);
}

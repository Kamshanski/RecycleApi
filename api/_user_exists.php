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
    public ?string $login = "";
    /** @throws Exception */
    public function processPost(Repository $repository, array $payload): BaseResponse {
        $this->login = $payload["login"];

        requireAllNonNullOrBlank("Cannot be empty: ", ["login" => $this->login]);

        $response = new UserExistsResponse($this->login);

        $response->exists = $repository->checkUserExists($payload["login"]);
        return $response;
    }

    protected function getEmptyResponse(): BaseResponse
    {
        return new UserExistsResponse($this->login);
    }
}

if (isEmpty(debug_backtrace())) {
    main(["POST" => new UserExistsProcessor()]);
}




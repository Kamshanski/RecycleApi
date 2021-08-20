<?php

include_once __DIR__ . "/../utils/utils.php";

abstract class PostProcessor extends AbstractProcessor {

    protected function processForResponse(Repository $repo): BaseResponse {
        return $this->processPost($repo, $this->getPostContent());
    }

    public abstract function processPost(Repository $repository, array $payload) : BaseResponse;


    //https://only-to-top.ru/blog/programming/2019-11-06-rest-api-php.html
    protected function addHeaders()  {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }

    protected function debugModeIsOn(): bool {
        return isset($_POST["debug_mode"]) && strtolower($_POST["debug_mode"]) === "true";
    }

    // https://stackoverflow.com/a/58679354/11103179
    // comment https://stackoverflow.com/a/7084677/11103179
    protected function getPostContent() : array {
        $payload = json_decode(file_get_contents('php://input'), true);
        if(json_last_error() == JSON_ERROR_NONE && !isEmpty($payload)) {
            return $payload;
        }
        return $_POST;
    }

}
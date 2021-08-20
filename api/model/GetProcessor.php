<?php

abstract class GetProcessor extends AbstractProcessor{
    protected function processForResponse(Repository $repo): BaseResponse {
        return $this->processGet($repo);
    }

    public abstract function processGet(Repository $repository) : BaseResponse;



    //https://only-to-top.ru/blog/programming/2019-11-06-rest-api-php.html
    protected function addHeaders() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");
        header("Content-Type: application/json");
    }

    protected function debugModeIsOn(): bool {
        return isset($_GET["debug_mode"]) && strtolower($_GET["debug_mode"]) === "true";
    }


}
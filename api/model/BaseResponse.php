<?php

class BaseResponse implements JsonTransformable {
    public $error = null;

    // https://www.php.net/manual/ru/function.get-object-vars.php
    function toJson(): string {
        return json_encode(get_object_vars($this));
    }

    public static function SUCCESS(): BaseResponse {
        return new BaseResponse();
    }

    public static function FAIL($error): BaseResponse {
        $response = new BaseResponse();
        $response->error = $error;
        return $response;
    }
}
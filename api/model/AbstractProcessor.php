<?php

abstract class AbstractProcessor {

    public function __construct() {
        if (!$this->debugModeIsOn()) {
            error_reporting(E_ERROR);
        }
    }

    public function run(Repository $repository) {
        $response = "";
        try {
            $response = $this->processForResponse($repository);
            http_response_code(200);
        } catch (Exception $ex) {
            $response = $this->getEmptyResponse();
            $response->error = $ex->getMessage();
            http_response_code(500);
        } finally {
            $this->addHeaders();
            echo $response->toJson();;
        }
    }

    /**
     * Main method to process request with repository provided.
     * @param Repository $repo
     * @return BaseResponse - response
     */
    protected abstract function processForResponse(Repository $repo) : BaseResponse;

    /**
     * Add headers to the response according to the request method
     */
    protected abstract function addHeaders();

    /**
     * @return true if "debug_mode=true" is passes as parameter in URL
     */
    protected abstract function debugModeIsOn() : bool;

    protected abstract function getEmptyResponse() : BaseResponse;

}

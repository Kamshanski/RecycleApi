<?php

include_once __DIR__ . "/model/PageController.php";

if (!debug_backtrace()) {
    exit();
}


/**
 * @param array<string, AbstractProcessor> $allowedRequestMethods - { Request Method => AbstractProcessor }
 */
function main(array $allowedRequestMethods) {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $processor = $allowedRequestMethods[$requestMethod];
    if (isset($processor) && $processor instanceof AbstractProcessor) {
        $repository = new Repository();
        $processor->run($repository);
    } else {
        http_response_code(405);
        echo "Allowed methods at ". __FILE__ . " : " . implode(", ", array_keys($allowedRequestMethods)) . ". Your request is $requestMethod." ;
        exit();
    }
}
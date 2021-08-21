<?php

include_once __DIR__ . "/_main.php";
include_once __DIR__ . "/utils/utils.php";
includeOnceAll(__DIR__ . "/model");


class RecycleSearchResponse extends BaseResponse {
    protected $fullMatch = array();
    protected $partialMatch = array();

    public function addFullMatches(RecycleList $items) {
        foreach ($items->getAll() as $key => $val) {
            $this->fullMatch[] = $val;
        }
    }

    public function addPartialMatches(RecycleList $items) {
        foreach ($items->getAll() as $key => $val) {
            $this->partialMatch[] = $val;
        }
    }

    function toJson(): string {
        if (isEmpty($this->fullMatch)) {
            $this->fullMatch = [];
            //throw new Exception("RecycleSearchResponse is empty");
        }
        return parent::toJson();
    }
}

class RecycleSearchProcessor extends GetProcessor {

    /*** @throws Exception */
    public function processGet(Repository $repository): BaseResponse {
        $id = $_GET["globalId"];
        $barcode = $_GET["barcode"];
        $barcodeType = $_GET["barcodeType"];

        if (!($id || $barcode || $barcodeType)) {
            throw new Exception("Query cannot be empty");
        }

        $list = $repository->getInfo($id, $barcode, $barcodeType);

        $response = new RecycleSearchResponse();
        $response->addFullMatches($list);

        return $response;
    }

    protected function getEmptyResponse(): BaseResponse {
        return new RecycleSearchResponse();
    }
}

if (isEmpty(debug_backtrace())) {
    main(["GET" => new RecycleSearchProcessor()]);
}




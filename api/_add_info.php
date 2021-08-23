<?php

include_once __DIR__ . "/_main.php";
include_once __DIR__ . "/utils/utils.php";
includeOnceAll(__DIR__ . "/model");

class AddRecycleResponse extends BaseResponse {
    protected $login = "";
    public $added = false;

    /**
     * @param string $login
     */
    public function __construct(string $login) {
        $this->login = $login;
    }
}

class AddRecycleProcessor extends PostProcessor {
    private ?string $login = "";

    // TODO: сделать добавление только

    /** @throws Exception */
    public function processPost(Repository $repository, array $payload): BaseResponse {
        $this->login    = $payload["login"];
        $globalId       = $payload["globalId"];
        $name           = $payload["name"];
        $barcode        = $payload["barcode"];
        $barcodeType    = $payload["barcodeType"];
        $barcodeInfo    = $payload["barcodeInfo"];
        $barcodeLink    = $payload["barcodeLink"];
        $productInfo    = $payload["productInfo"];
        $productType    = $payload["productType"];
        $productLink    = $payload["productLink"];
        $utilizeInfo    = $payload["utilizeInfo"];
        $utilizeLink    = $payload["utilizeLink"];

        requireAllNonNullOrBlank("Cannot be empty", [
            "login"         => $this->login ,
            "name"          =>  $name       ,
            "barcode"       =>  $barcode    ,
            "barcodeType"   =>  $barcodeType,
            "barcodeInfo"   =>  $barcodeInfo,
            "productInfo"   =>  $productInfo,
            "productType"   =>  $productType,
            "utilizeInfo"   =>  $utilizeInfo,
        ]);

        $response = new AddRecycleResponse($this->login);

        $response->added = $repository->putRecycle(
            new CandidateRecycleRecord(
                $this->login,
                $globalId   ,
                $name       ,
                $barcode    ,
                $barcodeType,
                $barcodeInfo,
                $barcodeLink,
                $productInfo,
                $productType,
                $productLink,
                $utilizeInfo,
                $utilizeLink
            )
        );

        return $response;
    }

    protected function getEmptyResponse(): BaseResponse {
        return new AddRecycleResponse($this->login);
    }
}

if (isEmpty(debug_backtrace())) {
    main(["POST" => new AddRecycleProcessor()]);
}
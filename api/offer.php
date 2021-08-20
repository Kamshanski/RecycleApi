<?php

use mysql_xdevapi\BaseResult;

include_once __DIR__ . "/_main.php";
include_once __DIR__ . "/utils/utils.php";
includeOnceAll(__DIR__ . "/model");

class RecycleOfferPostProcessor extends PostProcessor {
    private $login = "";

    /** @throws Exception */
    public function processPost(Repository $repository, array $payload): BaseResponse {
        $userId         = $payload["userId"];
        $this->login    = $payload["login"];
        $barcode        = $payload["barcode"];
        $barcodeType    = $payload["barcodeType"];

        requireAllNonNullOrBlank("Cannot be empty: ", [
            "barcode" => $barcode,
            "barcodeType" => $barcodeType,
            "login" => $this->login,
            "userId" => $userId
        ]);

        $offer = new RecycleOffer(
            $this->login,
            $payload["globalId"] ?? null,
            $payload["name"] ?? null,
            $barcode,
            $barcodeType,
            $payload["productInfo"] ?? null,
            $payload["utilizeInfo"] ?? null,
            date(DATE_ISO8601),
            $payload["image"] ?? null
        );

        $repository->putOffer(intval($userId), $offer);

        return BaseResponse::SUCCESS();
    }

    protected function getEmptyResponse(): BaseResponse {
        return new LoginResponse($this->login);
    }
}

if (isEmpty(debug_backtrace())) {
    main(["POST" => new RecycleOfferPostProcessor()]);
}






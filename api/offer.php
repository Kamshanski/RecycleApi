<?php


include_once __DIR__ . "/_main.php";
include_once __DIR__ . "/utils/utils.php";
includeOnceAll(__DIR__ . "/model");

class OfferPostResponse extends LoginResponse {
    public string $offerId = ""; // empty if error occurred
    public function __construct(?string $login, string $offerId = "") {
        parent::__construct($login);
        $this->offerId = $offerId;
    }
}

class RecycleOfferPostProcessor extends PostProcessor {
    private ?string $login = "";

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
            recycleTimestamp(),
            $payload["image"] ?? null
        );

        $offerId = $repository->putOffer(intval($userId), $offer);

        return new OfferPostResponse($this->login, $offerId);
    }

    protected function getEmptyResponse(): BaseResponse {
        return new OfferPostResponse($this->login);
    }
}

if (isEmpty(debug_backtrace())) {
    main(["POST" => new RecycleOfferPostProcessor()]);
}






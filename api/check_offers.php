<?php

include_once __DIR__ . "/_main.php";
include_once __DIR__ . "/utils/utils.php";
includeOnceAll(__DIR__ . "/model");

class CheckOffersResponse extends LoginResponse {
    public array $list = array();  // empty if error occurred
    public function __construct(?string $login, string $offerId = "") {
        parent::__construct($login);
        $this->offerId = $offerId;
    }
}
// accepts ?offerIds=["1","2",...]
class CheckOffersProcessor extends GetProcessor {
    private ?string $login = "";

    /** * @throws Exception */
    public function processGet(Repository $repository): BaseResponse {
        // todo: добавть проверку userId
        $this->login = $_GET["login"];
        $offerIds = json_decode($_GET["offerIds"],true);
        if ($offerIds != null && isEmpty($offerIds)) {
            $offerIdList = new OfferIdList();
            $offerIdList->addAll($offerIds);
            $reports = $repository->checkOffers($offerIdList);
            $response = new CheckOffersResponse($this->login);
            $response->list = $reports->getAll();
            return $response;
        } else {
            throw new Exception("Check offers request cannot be empty");
        }
    }

    protected function getEmptyResponse(): BaseResponse {
        return new CheckOffersResponse($this->login ?: "");
    }
}

if (isEmpty(debug_backtrace())) {
    main(["GET" => new CheckOffersProcessor()]);
}
<?php
include_once __DIR__ . "/EmptyObject.php";

// no need to store login for each record
class OfferReport {
    public string $offerId = "";
    public bool $isPresent = false;
    public string $processStatus = "UNSTUDIED";
    public ?string $processComment = null;
    public string $time = "";

    /**
     * @param bool $isPresent
     */
    public function __construct(string $offerId, bool $isPresent = true) {
        $this->isPresent = $isPresent;
        $this->offerId = $offerId;
    }
}
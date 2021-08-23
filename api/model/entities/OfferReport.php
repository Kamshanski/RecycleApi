<?php
include_once __DIR__ . "/EmptyObject.php";

// no need to store login for each record
class OfferReport {
    public string $offerId = "";
    public bool $isAccepted = false;
    public bool $isPresent = false;
    public string $time = "";

    /**
     * @param bool $isPresent
     */
    public function __construct(bool $isPresent)
    {
        $this->isPresent = $isPresent;
    }


}
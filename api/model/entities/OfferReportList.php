<?php

include_once __DIR__ . "/OfferReport.php";

class OfferReportList {
    private array $list = array();

    public function add(?OfferReport $value) {
        if ($value)
            $this->list[] = $value;
    }

    public function getAll() : array {
        return $this->list;
    }

    public function addAll(array $offerIds) {
        foreach ($offerIds as $offerId) {
            $this->add($offerId);
        }
    }
}
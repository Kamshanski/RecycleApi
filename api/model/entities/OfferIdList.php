<?php

class OfferIdList {
    private array $list = array();

    /**
     * @param array $list
     */
    public function __construct(?array $list = null) {
        if ($list && is_array($list)) {
            $this->addAll($list);
        }
    }

    public function add(?string $value) {
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
<?php

include_once __DIR__ . "/Recycle.php";

class RecycleList {
    private $list = [];
    public function add(?Recycle $item) {
        if ($item) {
            $this->list[] = $item;
        }
    }

    public function getAll() : array { return $this->list; }
}

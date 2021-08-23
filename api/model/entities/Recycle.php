<?php

include_once __DIR__ . "/EditLog.php";

class Recycle {
    public static function emptyInstance(string $barcode, string $barcodeType) {
        $recycle = new Recycle();
        $recycle->barcodeType = $barcodeType;
        $recycle->barcode = $barcode;
        return $recycle;
    }

    public string $globalId    = "";
    public string $name        = "";
    public string $barcode     = "";
    public string $barcodeType = "UNKNOWN";
    public ?string $barcodeInfo = "";
    public ?string $barcodeLink = "";
    public ?string $productInfo = "";
    public ?string $productType = "";
    public ?string $productLink = "";
    public ?string $utilizeInfo = "";
    public ?string $utilizeLink = "";
    public int $popularity  = 0;
    public EditLog $editLog;

    public function __construct() {
        $this->editLog = new EditLog();
    }


}
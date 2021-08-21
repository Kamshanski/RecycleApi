<?php

class CandidateRecycleRecord {
public $login = "";
public $globalId = null;
public $name = "";
public $barcode = "";
public $barcodeType = "";
public $barcodeInfo = "";
public $barcodeLink = null;
public $productInfo = "";
public $productType = "";
public $productLink = null;
public $utilizeInfo = "";
public $utilizeLink = null;

    /**
     * @param string $login
     * @param string|null $globalId
     * @param string $name
     * @param string $barcode
     * @param string $barcodeType
     * @param string $barcodeInfo
     * @param string|null $barcodeLink
     * @param string $productInfo
     * @param string $productType
     * @param string|null $productLink
     * @param string $utilizeInfo
     * @param string|null $utilizeLink
     */
    public function __construct(string $login, ?string $globalId, string $name,
                                string $barcode, string $barcodeType, string $barcodeInfo, ?string $barcodeLink,
                                string $productInfo, string $productType, ?string $productLink,
                                string $utilizeInfo, ?string $utilizeLink
    ) {
        $this->login = $login;
        $this->globalId = $globalId;
        $this->name = $name;
        $this->barcode = $barcode;
        $this->barcodeType = $barcodeType;
        $this->barcodeInfo = $barcodeInfo;
        $this->barcodeLink = $barcodeLink;
        $this->productInfo = $productInfo;
        $this->productType = $productType;
        $this->productLink = $productLink;
        $this->utilizeInfo = $utilizeInfo;
        $this->utilizeLink = $utilizeLink;
    }


}
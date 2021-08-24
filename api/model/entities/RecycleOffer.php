<?php /** @noinspection SqlNoDataSourceInspection */

include_once __DIR__ . "/../../utils/utils.php";

class RecycleOffer{
    public string $login        = "";
    public ?string $globalId    = null;
    public ?string $name        = null;
    public string $barcode      = "";
    public string $barcodeType  = "UNKNOWN";
    public ?string $productInfo = null;
    public ?string $utilizeInfo = null;
    public string $time         = "";
    public ?string $image       = null;

    public function __construct(
        string $login,
        ?string $globalId,
        ?string $name,
        string $barcode,
        string $barcodeType,
        ?string $productInfo,
        ?string $utilizeInfo,
        string $time,
        ?string $image
    ) {
        $this->login        = stringOrNull($login       );
        $this->globalId     = stringOrNull($globalId    );
        $this->name         = stringOrNull($name        );
        $this->barcode      = stringOrNull($barcode     );
        $this->barcodeType  = stringOrNull($barcodeType );
        $this->productInfo  = stringOrNull($productInfo );
        $this->utilizeInfo  = stringOrNull($utilizeInfo );
        $this->time         = stringOrNull($time        );
        $this->image        = stringOrNull($image       );
    }

}
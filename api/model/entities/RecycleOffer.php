<?php /** @noinspection SqlNoDataSourceInspection */

include_once __DIR__ . "/../../utils/utils.php";

class RecycleOffer{
    public $login      = "";
    public $globalId    = null;
    public $name        = null;
    public $barcode     = "";
    public $barcodeType = "UNKNOWN";
    public $productInfo = null;
    public $utilizeInfo = null;
    public $offerDate   = "";
    public $image       = null;

    public function __construct(
        string $login,
        ?string $globalId,
        ?string $name,
        string $barcode,
        string $barcodeType,
        ?string $productInfo,
        ?string $utilizeInfo,
        string $offerDate,
        ?string $image
    ) {
        $this->login        = stringOrNull($login       );
        $this->globalId     = stringOrNull($globalId    );
        $this->name         = stringOrNull($name        );
        $this->barcode      = stringOrNull($barcode     );
        $this->barcodeType  = stringOrNull($barcodeType );
        $this->productInfo  = stringOrNull($productInfo );
        $this->utilizeInfo  = stringOrNull($utilizeInfo );
        $this->offerDate    = stringOrNull($offerDate   );
        $this->image        = stringOrNull($image       );
    }

}
<?php

// todo: сделать ограничение на размер пароля, логина и тд. здесь в php

include_once __DIR__ . "/../utils/utils.php";
includeOnceAll(__DIR__ . "/../utils/query_builders");
includeOnceAll(__DIR__ . "/entities");
include_once __DIR__ . "/JsonTransformable.php";

class Database {
    private string $host       = "localhost";
    private string $username   = "id17404411_dawande";
    private string $password   = "#y4y^XB0PxA8u*&2";
    private string $db_name    = "id17404411_recycle";
    private string $info_table = "info";
    private string $offers_table = "new_offers";
    private string $users_table = "users";
    private string $processed_offers_table = "processed_offers";
    private $link;

    /**
     * Retrieves array of RecycleInfo. The first one is the most probable
     * @throws Exception if no parameters is passed
     * @return array of found items. Empty if nothing was found
     */
    public function getInfo(?string $globalId, ?string $barcode, ?string $barcodeType) : array {
        // TODO: проверку barcodeType

        $query = (new SelectQuery())
            ->in($this->info_table)
            ->str("globalId", $globalId)
            ->str("barcode", $barcode)
            ->str("barcodeType", $barcodeType)
            ->build();

        $list = array();
        if ($result = mysqli_query($this->link, $query)) {
            while($row = mysqli_fetch_assoc($result)){
                $item = new Recycle();
                $item->globalId    = $row["globalId"];
                $item->name        = $row["name"];
                $item->barcode     = $row["barcode"];
                $item->barcodeType = $row["barcodeType"];
                $item->barcodeInfo = $row["barcodeInfo"];
                $item->barcodeLink = $row["barcodeLink"];
                $item->productInfo = $row["productInfo"];
                $item->productType = $row["productType"];
                $item->productLink = $row["productLink"];
                $item->utilizeInfo = $row["utilizeInfo"];
                $item->utilizeLink = $row["utilizeLink"];
                $item->utilizeLink = $row["popularity"];
                // true is for parsing JSON object {} as array []. json_decode can't decode pure array [], so {} format in database is a workaround
                $item->editLog     = EditLog::fromJson($row["editLog"]);

                $list[] = $item;
            }
            mysqli_free_result($result);
        }

        return $list;
    }

    /**  @throws Exception
     * @noinspection PhpInconsistentReturnPointsInspection
     * @return null if OK, otherwise error message */
    public function recordExists(string $globalId) : bool {
        $query = (new CountQuery())
            ->in($this->info_table)
            ->str("globalId", $globalId)
            ->build();

        if ($result = mysqli_query($this->link, $query)) {
            $row = $result->fetch_row();
            return intval($row[0]) != 0;
        } else {
            throw_mysqli_error($this->link, "recordExists");
        }
    }

    /**  @throws Exception
     * @noinspection PhpInconsistentReturnPointsInspection
     * @return globalId */
    public function addRecycle(CandidateRecycleRecord $candidate) : string {
        $globalId = $this->createNewGlobalId();
        $insertQuery = (new InsertQuery())
            ->in($this->info_table)
            ->str("globalId", $globalId)
            ->str("name", $candidate->name)
            ->str("barcode", $candidate->barcode)
            ->str("barcodeType", $candidate->barcodeType)
            ->str("barcodeInfo", $candidate->barcodeInfo)
            ->str("barcodeLink", $candidate->barcodeLink)
            ->str("productInfo", $candidate->productInfo)
            ->str("productType", $candidate->productType)
            ->str("productLink", $candidate->productLink)
            ->str("utilizeInfo", $candidate->utilizeInfo)
            ->str("utilizeLink", $candidate->utilizeLink)
            ->str("popularity",  0)
            ->str("editLog", EditLog::fromArray([recycleTimestamp() => $candidate->login])->toJson())
            ->build();

        if (mysqli_query($this->link, $insertQuery)) {
            return $globalId;
        } else {
            throw_mysqli_error($this->link, "Add Recycle");
        }
    }

    /**  @throws Exception
     * @noinspection PhpInconsistentReturnPointsInspection
     * @return globalId */
    public function updateRecycle(CandidateRecycleRecord $candidate) : string {
        $previousRecord = $this->getInfo($candidate->globalId, null, null)[0];
        if (!($previousRecord instanceof Recycle)) {
            throw new Exception("Database return invalid array of " . get_class($previousRecord));
        }
        $editLog = $previousRecord->editLog;
        $editLog->add(recycleTimestamp(), $candidate->login);
        $updateQuery = (new UpdateQuery())
            ->in($this->info_table)
            ->update()
            ->str("name", $candidate->name)
            ->str("barcode", $candidate->barcode)
            ->str("barcodeType", $candidate->barcodeType)
            ->str("barcodeInfo", $candidate->barcodeInfo)
            ->str("barcodeLink", $candidate->barcodeLink)
            ->str("productInfo", $candidate->productInfo)
            ->str("productType", $candidate->productType)
            ->str("productLink", $candidate->productLink)
            ->str("utilizeInfo", $candidate->utilizeInfo)
            ->str("utilizeLink", $candidate->utilizeLink)
            ->str("popularity", $previousRecord->popularity)
            ->str("editLog", $editLog->toJson())
            ->where()
            ->str("globalId", $candidate->globalId)
            ->build();

        if (mysqli_query($this->link, $updateQuery)) {
            return $candidate->globalId;
        } else {
            throw_mysqli_error($this->link, "Update Recycle");
        }
    }
    /**  @throws Exception
     * @noinspection PhpInconsistentReturnPointsInspection
     * @return string new globalId  */
    public function createNewGlobalId() : string {
        while (true) {
            $id = random_str();
            $info = $this->getInfo($id, null, null);
            if (isEmpty($info)) {
                return $id;
            }
        }
    }

    /**  @throws Exception
     * @noinspection PhpInconsistentReturnPointsInspection
     * @return offerId if OK, otherwise error message */
    public function putOffer(int $userId, RecycleOffer $offer) : string {
        if (($offer->globalId || $offer->name || $offer->productInfo || $offer->utilizeInfo || $offer->image)) {
            if ($this->validateUser($offer->login, $userId)) {
                $offerId = $this->timeLoginId($offer->login);
                $query = (new InsertQuery())
                    ->in($this->offers_table)
                    ->str("offerId", $offerId)
                    ->str("login", $offer->login)
                    ->str("globalId", $offer->globalId)
                    ->str("name", $offer->name)
                    ->str("barcode", $offer->barcode)
                    ->str("barcodeType", $offer->barcodeType)
                    ->str("productInfo", $offer->productInfo)
                    ->str("utilizeInfo", $offer->utilizeInfo)
                    ->str("offerDate", $offer->offerDate)
                    ->str("image", $offer->image)
                    ->build();

                if (!mysqli_query($this->link, $query)) {
                    throw_mysqli_error($this->link, "Put Offer");
                }
                return $offerId;
            } else {
                throw new Exception("Login $offer->login does not match userId $userId");
            }
        } else {
            throw new Exception("Offer must not be empty");
        }
    }

    // year-month-day-hour-minutes-seconds-millis-micros-login
    // Id constructed to be absolutely unique, even if it's less efficient
    public function timeLoginId(string $login) : string {
        $fullMicro = microtime(false);
        $from = strpos($fullMicro, ".") + 1;
        $len = strpos($fullMicro, " ") - $from;
        $micro = substr($fullMicro, $from, $len);
        return gmdate('YmdHis') . $micro . $login;
    }

    public function checkOffers(string $offerId) : OfferReport {
        $selectQuery = (new SelectQuery())
            ->in($this->processed_offers_table)
            ->select("accepted")
            ->select("date")
            ->str("offerId", $offerId)
            ->build();

        if ($result = mysqli_query($this->link, $selectQuery)) {
            $row = mysqli_fetch_assoc($result);
            $report = null;
            if ($row) {
                $isAccepted = $row["isAccepted"];
                $time = $row["time"];
                if ($isAccepted == null || $time == null) {
                    throw new Exception("Inconsistent database offer report of $offerId");
                }
                $report = new OfferReport(true);
                $report->offerId = $offerId;
                $report->isAccepted = boolval($isAccepted);
                $report->time = strval($time);
            } else {
                $report = new OfferReport(false);
            }

            mysqli_free_result($result);
            return $report;
        }
    }

    /**  @throws Exception
     * @noinspection PhpInconsistentReturnPointsInspection */
    //https://stackoverflow.com/a/3613087/11103179
    public function userExists(string $login) : bool {
        $query = (new CountQuery())
            ->in($this->users_table)
            ->str("login", $login)
            ->build();

        if ($result = mysqli_query($this->link, $query)) {
            $row = $result->fetch_row();
            return intval($row[0]) != 0;
        } else {
            throw_mysqli_error($this->link, "UserExists");
        }
    }

    /**  @throws Exception
     * @noinspection PhpInconsistentReturnPointsInspection */
    //https://www.php.net/manual/ru/function.password-hash.php
    public function addUser(string $login, string $password, string $mac) : User {
        $hash = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);

        $insertQuery = (new InsertQuery())
            ->in($this->users_table)
            ->str("login", $login)
            ->str("hash", $hash)
            ->str("mac", $mac)
            ->build();

        if (mysqli_query($this->link, $insertQuery)) {
            $userId = $this->getUserId($login, $password);
            return new User($login, $userId, $hash, $mac);
        } else {
            throw_mysqli_error($this->link, "Add User");
        }
    }

    /**  @throws Exception
     * @noinspection PhpInconsistentReturnPointsInspection */
    public function getUserId(string $login, string $password) : int {
        $selectQuery = (new SelectQuery())
            ->in($this->users_table)
            ->str("login", $login)
            ->limit(1)
            ->build();

        if ($result = mysqli_query($this->link, $selectQuery)) {
            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row["hash"]))
                    return intval($row["userId"]);
                else
                    throw new Exception("Password is incorrect");
            } else
                throw new Exception("No user found with login $login");
        } else {
            throw_mysqli_error($this->link, "Get User Id");
        }
    }

    /**  @throws Exception
     * @noinspection PhpInconsistentReturnPointsInspection */
    public function validateUser(string $login, int $userId) : bool {
        $selectQuery = (new SelectQuery())
            ->in($this->users_table)
            ->str("login", $login)
            ->limit(1)
            ->build();
        if ($result = mysqli_query($this->link, $selectQuery)) {
            if ($row = mysqli_fetch_assoc($result)) {
                return $row["userId"] == $userId;
            } else
                throw new Exception("No user found with login $login");
        } else {
            throw_mysqli_error($this->link, "validate user");
        }
    }

    /**  @throws Exception
     *   @noinspection PhpInconsistentReturnPointsInspection */
    public function deleteUser(string $login, int $userId) {
        $deleteQuery = (new DeleteQuery())
            ->in($this->users_table)
            ->str("login", $login)
            ->int("userId", $userId)
            ->build();

        if (mysqli_query($this->link, $deleteQuery)) {
            return true;
        } else {
            throw_mysqli_error($this->link, "delete user");
        }
    }

    // ------------- Other --------------

    /**
     * @throws Exception
     */
    public function __construct(){
        $this->link = mysqli_connect(
            $this->host    ,
            $this->username,
            $this->password,
            $this->db_name );
            
        if (!$this->link) {
            throw new Exception("Невозможно подключиться к базе данных. Код ошибки: " . mysqli_connect_error());
        }
    }
    
    function __destruct() {
        if ($this->link) {
            mysqli_close($this->link);
        }
    }

}

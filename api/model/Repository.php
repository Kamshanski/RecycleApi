<?php
include_once __DIR__ . "/../utils/utils.php";
includeOnceAll(__DIR__ . "/entities");
include_once __DIR__ . "/Database.php";

class Repository {
    private $_db;

    protected function getDb() : Database {
        if (!$this->_db) {
            $this->_db = new Database();
        }
        return $this->_db;
    }

    /**@throws Exception */
    function getInfo(?string $id, ?string $barcode, ?string $barcodeType) : RecycleList {
        $list = $this->getDb()->getInfo($id, $barcode, $barcodeType);
        $recycleList = new RecycleList();
        if (isEmpty($list)) {
            $recycleList->add(new Recycle());
        } else {
            foreach ($list as $item) {
                $recycleList->add($item);
            }
        }
        return $recycleList;
    }

    /**@throws Exception */
    function putOffer(int $userId, RecycleOffer $offer) {
        $this->getDb()->putOffer($userId, $offer);
    }

    /** @throws Exception */
    public function addNewUser(string $login, string $password, string $mac) : User {
        if ($this->getDb()->userExists($login)) {
            throw new Exception("User with login $login already exists");
        } else {
            return $this->getDb()->addUser($login, $password, $mac);
        }
    }

    /** @throws Exception */
    public function checkUserExists(string $login) : bool {
        return $this->getDb()->userExists($login);
    }

    /**
     * @param $login
     * @throws Exception
     * @return true if user was found and deleted
     */
    public function deleteUser(string $login, int $userId) : bool {
        if ($this->getDb()->validateUser($login, $userId)) {
            throw new Exception("User with login $login is already absent");
        } else {
            return $this->getDb()->deleteUser($login, $userId);
        }
    }
}
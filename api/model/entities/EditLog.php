<?php
include_once __DIR__ . "/../JsonTransformable.php";
include_once __DIR__ . "/EmptyObject.php";

class EditLog implements JsonSerializable, JsonTransformable {
    public static function fromJson(string $json) : EditLog {
        $array = json_decode($json, true);
        return EditLog::fromArray($array);
    }
    public static function fromArray(array $log) : EditLog {
        $editLog = new EditLog();
        $editLog->addAll($log);
        return $editLog;
    }

    public $log = array();

    public function add(string $time, string $author) {
        $this->log[$time] = $author;
    }

    public function addAll(array $log) {
        foreach ($log as $time => $author) {
            if (is_string($time) && is_string($author)) {
                $this->add($time, $author);
            }
        }
    }

    public function jsonSerialize() {
        if (isEmpty($this->log)) {
            return new EmptyObject();
        } else {
            return $this->log;
        }
    }

    function toJson(): string {
        return json_encode($this);
    }
}
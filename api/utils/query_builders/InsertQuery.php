<?php

class InsertQuery {
    public $tableName = "";
    public $strings = array();
    public $ints = array();


    public function in(string $tableName) : InsertQuery {
        $this->tableName = $tableName;
        return $this;
    }

    public function str(string $name, ?string $value) : InsertQuery {
        if ($value) {
            $this->strings[$name] = $value;
        }
        return $this;
    }

    public function int(string $name, ?int $value) : InsertQuery {
        if ($value) {
            $this->ints[$name] = $value;
        }
        return $this;
    }

    public function build() : string {
        if (!$this->tableName)
            throw new Exception("Table name is Null");

        if (isEmpty($this->strings) && isEmpty($this->ints)) {
            throw new Exception("Insert Query is empty");
        }
        $names = "";
        $values = "";

        foreach ($this->strings as $name => $value) {
            $names .= "`$name`, ";
            $values .= "'$value', ";
        }

        $query = "INSERT INTO `$this->tableName` (" . rtrim($names, ", ") . ") VALUES (" . rtrim($values, ", ") . ")";

        return $query;
    }
    
}
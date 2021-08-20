<?php

class DeleteQuery {
    private $tableName;
    private $strings = array();
    private $ints = array();

    public function in(?string $tableName) : DeleteQuery {
        if ($tableName)
            $this->tableName = $tableName;
        return $this;
    }

    public function str(string $name, ?string $value) : DeleteQuery {
        if ($value)
            $this->strings[$name] = $value;
        return $this;
    }

    public function int(?string $name, ?int $value) : DeleteQuery {
        if ($value)
            $this->ints[$name] = $value;
        return $this;
    }

    public function build() : string {
        if (!$this->tableName)
            throw new Exception("Table name is Null");
        if (isEmpty($this->strings) && isEmpty($this->ints)) {
            throw new Exception("Query is empty");
        }

        $query = "DELETE FROM `$this->tableName` WHERE ";

        foreach ($this->ints as $name => $int) {
            $query .= "`$name` = $int AND ";
        }

        foreach ($this->strings as $name => $str) {
            $query .= "`$name` = '$str' AND ";
        }

        $query = rtrim($query, "AND ");

        return $query;
    }

}
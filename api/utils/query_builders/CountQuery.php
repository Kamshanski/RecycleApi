<?php

class CountQuery {
    private $tableName= null;
    private $strings = array();
    private $ints = array();

    public function in(string $tableName) : CountQuery {
        $this->tableName = $tableName;
        return $this;
    }

    public function str(?string $name, ?string $value) : CountQuery {
        if ($value)
            $this->strings[$name] = $value;
        return $this;
    }

    public function int(?string $name, ?int $value) : CountQuery {
        if ($value)
            $this->ints[$name] = $value;
        return $this;
    }

    public function build() : string {
        if (!$this->tableName)
            throw new Exception("Table name is Null");

        if (isEmpty($this->strings)) {
            throw new Exception("Query is empty");
        }

        $query = "SELECT COUNT(*) FROM `$this->tableName` WHERE ";

        foreach ($this->strings as $name => $str) {
            $query .= "`$name` LIKE '$str' AND ";
        }

        foreach ($this->ints as $name => $int) {
            $query .= "`$name` = $int AND ";
        }

        $query = rtrim($query, "AND ");

        return $query;
    }

}
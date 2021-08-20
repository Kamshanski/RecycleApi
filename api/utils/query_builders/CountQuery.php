<?php

class CountQuery {
    private $tableName= null;
    private $equalList = array();

    public function in(string $tableName) : CountQuery {
        $this->tableName = $tableName;
        return $this;
    }

    public function equals(?string $name, ?string $value) : CountQuery {
        if ($value)
            $this->equalList[$name] = $value;
        return $this;
    }

    public function build() : string {
        if (!$this->tableName)
            throw new Exception("Table name is Null");

        if (isEmpty($this->equalList)) {
            throw new Exception("Query is empty");
        }

        $query = "SELECT COUNT(*) FROM `$this->tableName` WHERE ";

        foreach ($this->equalList as $name => $equalValue) {
            $query .= "`$name` = $equalValue AND ";
        }

        $query = rtrim($query, "AND ");

        return $query;
    }

}
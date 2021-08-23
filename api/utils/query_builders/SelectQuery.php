<?php
include_once __DIR__ . "/../utils.php";

class SelectQuery {
    private $select = array();
    private $tableName;
    private $strings = array();
    private $ints = array();
    private $limit = 5;

    public function select(?string $select) : SelectQuery {
        if ($select)
            $this->select[] = $select;
        return $this;
    }

    public function in(?string $tableName) : SelectQuery {
        if ($tableName)
            $this->tableName = $tableName;
        return $this;
    }

    public function str(string $name, ?string $value) : SelectQuery {
        if ($value)
            $this->strings[$name] = $value;
        return $this;
    }

    public function int(?string $name, ?int $value) : SelectQuery {
        if ($value)
            $this->ints[$name] = $value;
        return $this;
    }

    public function limit(?int $limit) : SelectQuery {
        if ($limit)
            $this->limit = $limit;
        return $this;
    }

    public function build() : string {
        if (!$this->tableName)
            throw new Exception("Table name is Null");
        if (isEmpty($this->strings) && isEmpty($this->ints)) {
            throw new Exception("Query is empty");
        }

        $query = "SELECT ";

        if (isEmpty($this->select)) {
            $query .= "*";
        } else {
            foreach ($this->ints as $field) {
                $query .= "`$field`, ";
            }
            $query .= rtrim($query, ", ");
        }

        $query .= " FROM `$this->tableName` WHERE ";

        foreach ($this->ints as $name => $int) {
            $query .= "`$name` = $int AND ";
        }

        foreach ($this->strings as $name => $str) {
            $query .= "`$name` = '$str' AND ";
        }

        $query = rtrim($query, "AND ");

        $query .= " LIMIT $this->limit";

        return $query;
    }
}
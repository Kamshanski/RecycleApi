<?php

class UpdateQuery {
    public $tableName = "";
    public $setCase = true;
    public $whereStrings = array();
    public $whereInts = array();
    public $setStrings = array();
    public $setInts = array();

    public function in(string $tableName) : UpdateQuery {
        $this->tableName = $tableName;
        return $this;
    }

    public function update() : UpdateQuery {
        $this->setCase = true;
        return $this;
    }

    // Just to separate set and where parts
    public function where() : UpdateQuery {
        $this->setCase = false;
        return $this;
    }

    public function int(string $name, ?int $value) : UpdateQuery {
        if ($value) {
            if ($this->setCase) {
                $this->setInts[$name] = $value;
            } else {
                $this->whereInts[$name] = $value;
            }
        }
        return $this;
    }
    public function str(string $name, ?string $value) : UpdateQuery {
        if ($value) {
            if ($this->setCase) {
                $this->setStrings[$name] = $value;
            } else {
                $this->whereStrings[$name] = $value;
            }
        }
        return $this;
    }

    public function build() : string {
        if (!$this->tableName)
            throw new Exception("Table name is Null");

        if (isEmpty($this->whereStrings) && isEmpty($this->whereInts) ) {
            throw new Exception("Update Query `where` is empty");
        }
        if (isEmpty($this->setStrings)) {
            throw new Exception("Update Query `set` is empty");
        }

        $query = "UPDATE `$this->tableName` SET ";

        foreach ($this->setStrings as $name => $newVal) {
            $query .= "`$name` = '$newVal', ";
        }

        $query = rtrim($query, ", ");
        $query .= " WHERE ";

        foreach ($this->whereInts as $name => $int) {
            $query .= "`$name` = $int AND ";
        }

        foreach ($this->whereStrings as $name => $str) {
            $query .= "`$name` = '$str' AND ";
        }

        $query = rtrim($query, "AND ");

        return $query;
    }
}
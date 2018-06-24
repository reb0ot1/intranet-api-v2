<?php

namespace Employees\Adapter;

class DatabaseStatement implements DatabaseStatementInterface
{
    private $stmt;

    public function __construct(\PDOStatement $stmt)
    {
        $this->stmt = $stmt;
    }


    public function execute(array $args = []) : bool
    {
        return $this->stmt->execute($args);
    }

    public function bindValue($param, $value, $type) : bool {
        return $this->stmt->bindValue($param, $value, $type);
    }

    public function fetch()
    {
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchAll()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchObject(string $class)
    {
        return $this->stmt->fetchObject($class);
    }

    public function fetchColumn()
    {
        return $this->stmt->fetchColumn();
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

}
<?php

namespace Employees\Adapter;

interface DatabaseInterface
{
    /**
     * @param $statement
     * @return DatabaseStatementInterface
     */
    public function prepare($statement) : DatabaseStatementInterface;

    public function lastInsertId();

    public function beginTransaction();

    public function commit();

    public function rollBack();
}
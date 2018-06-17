<?php

namespace Employees\Adapter;

interface DatabaseInterface
{
    /**
     * @param $statement
     * @return DatabaseStatementInterface
     */
    public function prepare($statement) : DatabaseStatementInterface;

    public function lastId();

    public function beginTransaction();

    public function commit();

    public function rollBack();
}
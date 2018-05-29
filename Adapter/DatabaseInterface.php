<?php

namespace Employees\Adapter;

interface DatabaseInterface
{
    /**
     * @param $statement
     * @return DatabaseStatementInterface
     */
    public function prepare($statement) : DatabaseStatementInterface;
}
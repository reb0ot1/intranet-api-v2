<?php
namespace Employees\Adapter;

interface DatabaseStatementInterface
{
    public function execute(array $args = []) : bool;

    public function bindValue($param, $value, $type) : bool ;

    public function fetch();

    public function fetchAll();

    public function fetchObject(string $class);

    public function fetchColumn();

    public function rowCount();
}
<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 17.10.2017 г.
 * Time: 8:55
 */

namespace Employees\Services;


interface CreatingQueryServiceInterface
{
    public function setQueryUpdateEmp($theId, $whereFilter);

    public function getQuery() : string;

    public function getValues() : array;

    public function setValues($values);
}
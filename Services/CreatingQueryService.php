<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 16.10.2017 г.
 * Time: 22:23
 */

namespace Employees\Services;

use Employees\Adapter\DatabaseInterface;
use Employees\Config\DbConfig;
use Employees\Models\Binding\Emp\EmpBindingModel;

class CreatingQueryService implements CreatingQueryServiceInterface
{

    private $query;
    private $arrayValues = array();
    private $columnNames = array();

//    public function __construct($arrayWithValues)
//    {
//        $this->columnNames = $arrayWithValues;
//    }


    public function setQueryUpdateEmp($theId, $whereFilter) {

        $str = "";

        foreach ($this->columnNames as $key=>$value) {
            if (!empty($value)) {

                $str .= $key."=?, ";
                $this->arrayValues[] = $value;
            }
        }
        $this->arrayValues[] = $theId;
        $this->query = substr($str, 0, strlen($str)-2);
        $this->query .= " WHERE ".$whereFilter;
    }

    public function getQuery(): string
    {
        // TODO: Implement getQuery() method.

        return $this->query;
    }

    public function getValues(): array
    {
        // TODO: Implement getValues() method.

        return $this->arrayValues;
    }

    public function setValues($values)
    {
        $this->columnNames = $values;
    }

}
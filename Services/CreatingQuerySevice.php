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

class CreatingQuerySevice implements CreatingQueryServiceInterface
{

    private $query;
    private $arrayValues = array();
    private $columnNames = array();
    private $db_name;
    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
        $this->db_name = DbConfig::DB_NAME;
    }

//    public function test1() {
//
//        $query = "SELECT COLUMN_NAME
//                  FROM INFORMATION_SCHEMA.COLUMNS
//                  WHERE TABLE_SCHEMA = ?
//                  AND TABLE_NAME = ?";
//
//        $stmt = $this->db->prepare($query);
//        $stmt->execute([$this->db_name, "employees"]);
//
//        $result = $stmt->fetchAll();
//
//        return $result;
//    }

    private function fulfillArrayUpdEmp(EmpBindingModel $bindingModel) {

        if ($bindingModel->getFirstName() !== NULL) {
            $this->columnNames["first_name"] = $bindingModel->getFirstName();
        }

        if ($bindingModel->getLastName() !== NULL) {
            $this->columnNames["last_name"] = $bindingModel->getLastName();
        }

        if ($bindingModel->getPosition() !== NULL) {
            $this->columnNames["position"] = $bindingModel->getPosition();
        }

        if ($bindingModel->getTeam() !== NULL) {
            $this->columnNames["team"] = $bindingModel->getTeam();
        }

        if ($bindingModel->getStartDate() !== NULL) {
            $this->columnNames["start_date"] = $bindingModel->getStartDate();
        }

        if ($bindingModel->getBirthday() !== NULL) {
            $this->columnNames["birthday"] = $bindingModel->getBirthday();
        }

        if ($bindingModel->getBirthday() !== NULL) {
            $this->columnNames["active"] = $bindingModel->getActive();
        }
    }

    public function setQueryUpdateEmp(EmpBindingModel $bindingModel) {

        $this->fulfillArrayUpdEmp($bindingModel);
        $str = "";
        foreach ($this->columnNames as $key=>$value) {
            $str .= $key."=?, ";
            $this->arrayValues[] = $value;
        }
        $this->arrayValues[] = $bindingModel->getId();
        $this->query = substr($str, 0, strlen($str)-2);
        $this->query .= " WHERE id = ?";
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
        // TODO: Implement setValues() method.
    }
}
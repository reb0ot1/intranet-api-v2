<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 18/08/2017
 * Time: 16:11
 */

namespace Employees\Services;


use Employees\Models\Binding\Emp\EmpBindingModel;

interface EmployeesServiceInterface
{

    public  function getListStatus($active, $id=null);

    public function addEmp(EmpBindingModel $model);

    public function getEmp($id);

    public function updEmp(EmpBindingModel $empBindingModel);

    public function removeEmp($empId) : bool;

    public function getEmailBodyForEmployeeCreation();

    public function addEmployeeEducationGroups($employeeId, $groups);

    public function updateEmployeeEducationGroups($employeeId, $groups);

    public function addEmployeeHobbyGroups($employeeId, $groups);

    public function updateEmployeeHobbyGroups($employeeId, $groups);

}
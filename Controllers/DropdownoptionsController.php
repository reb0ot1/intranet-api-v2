<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 8.6.2018 г.
 * Time: 23:53
 */

namespace Employees\Controllers;

use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\EmployeesServiceInterface;
use Employees\Services\EncryptionServiceInterface;
use Employees\Services\ImageFromBinServiceInterface;
use Employees\Core\DataReturnInterface;

class DropdownoptionsController
{

    private $employeeService;
    private $encryptionService;
    private $authenticationService;
    private $binaryImage;
    private $dataReturn;

    public function __construct(EmployeesServiceInterface $employeesService,
                                EncryptionServiceInterface $encryptionService,
                                AuthenticationServiceInterface $authenticationService,
                                ImageFromBinServiceInterface $binService,
                                DataReturnInterface $dataReturn)
    {
        $this->employeeService = $employeesService;
        $this->encryptionService = $encryptionService;
        $this->authenticationService = $authenticationService;
        $this->binaryImage = $binService;
        $this->dataReturn = $dataReturn;
    }

    public function allselectfields()
    {
        return $this->dataReturn->jsonData(["companies"=>array(array(["optionId"=>1, "optionName"=>"cQuest"],["optionId"=>2, "optionName"=>"gemSeek"]))]);
    }
}
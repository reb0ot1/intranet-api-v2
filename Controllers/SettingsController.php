<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 2.6.2018 г.
 * Time: 21:26
 */

namespace Employees\Controllers;


use Employees\Core\DataReturnInterface;
use Employees\Models\DB\Subgroups;
use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\EmployeesServiceInterface;
use Employees\Services\EncryptionServiceInterface;
use Employees\Services\ImageFromBinServiceInterface;
use Employees\Services\SettingsDataServiceInterface;

class SettingsController
{

    private $employeeService;
    private $encryptionService;
    private $authenticationService;
    private $dataReturn;
    private $settingsDataService;

    public function __construct(EmployeesServiceInterface $employeesService,
                                EncryptionServiceInterface $encryptionService,
                                AuthenticationServiceInterface $authenticationService,
                                ImageFromBinServiceInterface $binService,
                                DataReturnInterface $dataReturn,
                                SettingsDataServiceInterface $settingsDataService)
    {
        $this->employeeService = $employeesService;
        $this->encryptionService = $encryptionService;
        $this->authenticationService = $authenticationService;
        $this->dataReturn = $dataReturn;
        $this->settingsDataService = $settingsDataService;
    }

    public function getDropDownOptions($dropdown)
    {
        $sub_companies = $this->settingsDataService->$dropdown();

        $selectOptions = [];

        /**
         * @var Subgroups $sub_company
         */
        foreach ($sub_companies as $sub_company) {

            $arr = array("id"=>$sub_company->getId(), "optionName"=> $sub_company->getName());

            $selectOptions[] = $arr;
        }

        return $this->dataReturn->jsonData($selectOptions);

    }
}
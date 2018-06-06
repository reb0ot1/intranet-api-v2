<?php

namespace Employees\Controllers;



use Employees\Models\Binding\Emp\EmpBindingModel;
use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\EmployeesServiceInterface;
use Employees\Services\EncryptionServiceInterface;
use Employees\Services\ImageFromBinServiceInterface;
use Employees\Config\DefaultParam;
use Employees\Core\DataReturnInterface;

class EmployeesController
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

    public function find($id = null)
    {
                $list =  $this->employeeService->getListStatus("yes", $id);

        if (is_array($list)) {

            foreach ($list as $key => $value) {

                if (array_key_exists("image", $list[$key])) {
                    $list[$key]["image"] = DefaultParam::ServerRoot.DefaultParam::EmployeeContainer.$list[$key]["image"];
                }
                if (array_key_exists("avatar", $list[$key])) {
                    $list[$key]["avatar"] = DefaultParam::ServerRoot.DefaultParam::EmployeeContainer.$list[$key]["avatar"];
                }
                if (array_key_exists("photo", $list[$key])) {
                    $list[$key]["photo"] = DefaultParam::ServerRoot.DefaultParam::EmployeeContainer.$list[$key]["photo"];
                }
            }
        }

        return $this->dataReturn->jsonData($list);

    }

    public function removeEmployee($id) {
         if ($this->authenticationService->isTokenCorrect()) {

            if ($this->employeeService->removeEmp($id)) {
                return $this->dataReturn->jsonData(["id"=>$id]);
            } else {
                return $this->dataReturn->errorResponse(400,"The employee was not removed. Please try again");
            }
         }

         return $this->dataReturn->errorResponse(401);
    }


    public function addemployee(EmpBindingModel $employeeBindingModel)
    {

        if ($this->authenticationService->isTokenCorrect()) {

        $md5string = $this->encryptionService->md5generator($employeeBindingModel->getFirstName().
            $employeeBindingModel->getLastName().
            $employeeBindingModel->getBirthday().time());


            if ($this->binaryImage->createImage($employeeBindingModel->getImage(), DefaultParam::EmployeeContainer, $md5string, "jpg")) {
                $this->binaryImage->createImage($employeeBindingModel->getAvatar(),DefaultParam::EmployeeContainer, "av".$md5string, "jpg");
                $this->binaryImage->createImage($employeeBindingModel->getPhoto(),DefaultParam::EmployeeContainer, "ph".$md5string, "jpg");
                $employeeBindingModel->setImage($md5string.".jpg");
                $employeeBindingModel->setAvatar("av".$md5string.".jpg");
                $employeeBindingModel->setPhoto("ph".$md5string.".jpg");
                if ($this->employeeService->addEmp($employeeBindingModel, $md5string)) {
                    $empArrray = $this->employeeService->getEmpByStrId($md5string);

//                    $updateEmpId = $this->employeeService->updateAddInfoId($md5string, $empArrray["id"]);
                    $empArrray["image"] = DefaultParam::ServerRoot.DefaultParam::EmployeeContainer.$empArrray['image'];
                    $empArrray["avatar"] = DefaultParam::ServerRoot.DefaultParam::EmployeeContainer.$empArrray['avatar'];
                    $empArrray["photo"] = DefaultParam::ServerRoot.DefaultParam::EmployeeContainer.$empArrray['photo'];

                    return $this->dataReturn->jsonData($empArrray);

                } else {
                    $this->binaryImage->removeImage(DefaultParam::EmployeeContainer.$md5string.".jpg");
                    $this->binaryImage->removeImage(DefaultParam::EmployeeContainer."av".$md5string.".jpg");
                    $this->binaryImage->removeImage(DefaultParam::EmployeeContainer."ph".$md5string.".jpg");
                    return $this->dataReturn->errorResponse(400,"Add new employee failed");
                }
            } else {
                    return $this->dataReturn->errorResponse(400, "Image upload failed");
            }
        }

        return $this->dataReturn->errorResponse(401);

    }

    public function getemployee($id)
    {
        return $this->dataReturn->jsonData($this->employeeService->getEmp($id));
    }


    public function updateemployee($theid, EmpBindingModel $empBindingModel)
    {

        if ($this->authenticationService->isTokenCorrect()) {

            $empBindingModel->setId($theid);
            $employee = $this->employeeService->getEmp($theid);

            ////////////////////////////// NEED UPDATE////////////////////////////////////////////////////////////////////////////////////////////////////
            $oldImage = $employee["image"];
            $oldImageAv = $employee["avatar"];
            $oldImagePh = $employee["photo"];


            $isBinaryImage = preg_match("/^data:image\/(png|jpeg);base64,/", $empBindingModel->getImage()) > 0 ? true : false;
            $isBinaryAvatar = preg_match("/^data:image\/(png|jpeg);base64,/", $empBindingModel->getAvatar()) > 0 ? true : false;
            $isBinaryPhoto = preg_match("/^data:image\/(png|jpeg);base64,/", $empBindingModel->getPhoto()) > 0 ? true : false;

            $md5string = $this->encryptionService->md5generator($empBindingModel->getFirstName() .
                $empBindingModel->getLastName() .
                $empBindingModel->getBirthday() . time());

            if ($isBinaryImage) {
                $this->binaryImage->createImage($empBindingModel->getImage(), DefaultParam::EmployeeContainer, $md5string, "jpg");
                $empBindingModel->setImage($md5string . ".jpg");
            } else {
                $empBindingModel->setImage($oldImage);
            }

            if ($isBinaryAvatar) {
                $this->binaryImage->createImage($empBindingModel->getAvatar(), DefaultParam::EmployeeContainer, "av".$md5string, "jpg");
                $empBindingModel->setAvatar("av".$md5string . ".jpg");
            } else {
                $empBindingModel->setAvatar($oldImageAv);
            }

            if ($isBinaryPhoto) {

                $this->binaryImage->createImage($empBindingModel->getPhoto(), DefaultParam::EmployeeContainer, "ph".$md5string, "jpg");
                $empBindingModel->setPhoto("ph".$md5string . ".jpg");
            } else {
                $empBindingModel->setPhoto($oldImagePh);
            }


            if ($this->employeeService->updEmp($empBindingModel)) {
                if ($isBinaryImage) {

                    $this->binaryImage->removeImage(DefaultParam::EmployeeContainer . $oldImage);
                }
                if ($isBinaryAvatar) {

                    $this->binaryImage->removeImage(DefaultParam::EmployeeContainer . $oldImageAv);
                }
                if ($isBinaryPhoto) {
                    $this->binaryImage->removeImage(DefaultParam::EmployeeContainer . $oldImagePh);
                }

                $updatedEmployee = $this->employeeService->getEmp($empBindingModel->getId());

                $updatedEmployee["image"] = DefaultParam::ServerRoot . DefaultParam::EmployeeContainer . $updatedEmployee["image"];
                $updatedEmployee["avatar"] = DefaultParam::ServerRoot . DefaultParam::EmployeeContainer . $updatedEmployee["avatar"];
                $updatedEmployee["photo"] = DefaultParam::ServerRoot . DefaultParam::EmployeeContainer . $updatedEmployee["photo"];
                //                print_r(json_encode(array("employees" => $updatedEmployee)));

                return $this->dataReturn->jsonData($updatedEmployee);

            }
            return $this->dataReturn->errorResponse(400, "The update was unsuccessful");

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            return $this->dataReturn->errorResponse(401);
    }

}
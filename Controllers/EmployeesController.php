<?php

namespace Employees\Controllers;



use Employees\Models\Binding\Emp\EmpBindingModel;
use Employees\Models\Binding\Employees\EmployeesBindingModel;
use Employees\Models\DB\Email;
use Employees\Models\DB\Employee;
use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\EmailService;
use Employees\Services\EmployeesService;
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

            $imageName = strtolower($employeeBindingModel->getFirstName()."_".
                $employeeBindingModel->getLastName()."_".
                $this->encryptionService->md5generator(time()));

            $imageDefault = $employeeBindingModel->getGender() == 1 ? "defaultf.png" : "defaultm.png";
            $ImageNamesContainer = [];

            $employeePictures = $this->binaryImagesSentForUpload($employeeBindingModel, $imageName);

            if (count($employeePictures)> 0) {
                if (count($this->binaryImage->checkBinaryData($employeePictures)) != count($employeePictures)) {
                    return $this->dataReturn->errorResponse(400, "Image upload failed. Please try again.");
                }

                if (!$this->binaryImage->checkImageType($employeePictures)) {
                    return $this->dataReturn->errorResponse(400, "Image upload failed. Please use only .jpg, .jpeg or .png formats.");
                }

                $uploadedImages = $this->binaryImage->createImage($employeePictures, dirname(__DIR__)."\\webroot\\images\\");

                if (count($uploadedImages) != count($employeePictures)) {
                    $this->binaryImage->removeImage($uploadedImages, dirname(__DIR__)."\\webroot\\images\\");
                    return $this->dataReturn->errorResponse(400, "Image upload failed. Please try again.");
                }

                $ImageNamesContainer = $uploadedImages;

            }

            $employeeBindingModel->setImage(array_key_exists("image_".$imageName, $ImageNamesContainer) ? $ImageNamesContainer["image_".$imageName] : $imageDefault);
            $employeeBindingModel->setAvatar(array_key_exists("avatar_".$imageName, $ImageNamesContainer) ? $ImageNamesContainer["avatar_".$imageName] : $imageDefault);
            $employeeBindingModel->setPhoto(array_key_exists("photo_".$imageName, $ImageNamesContainer) ? $ImageNamesContainer["photo_".$imageName] : $imageDefault);

            try {
                $lastEmployeeAddedId = $this->employeeService->addEmp($employeeBindingModel);
                $employeeData = $this->employeeService->getEmp($lastEmployeeAddedId);

                $email = new EmailService();

                $email->sendEmail($this->emailEmployeePreparation($lastEmployeeAddedId));

                return $this->dataReturn->jsonData($employeeData);


            } catch (\Exception $e) {
                if (count($ImageNamesContainer) > 0 ) {
                    $this->binaryImage->removeImage($ImageNamesContainer,dirname(__DIR__)."\\webroot\\images\\");
                }
                return $this->dataReturn->errorResponse(400,$e->getMessage());
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
            if (!$employee) {
                return $this->dataReturn->errorResponse(400);
            }

            $oldImage = str_replace(DefaultParam::ServerRoot.DefaultParam::EmployeeContainer,"",$employee["image"]);
            $oldImageAv = str_replace(DefaultParam::ServerRoot.DefaultParam::EmployeeContainer,"",$employee["avatar"]);
            $oldImagePh = str_replace(DefaultParam::ServerRoot.DefaultParam::EmployeeContainer,"",$employee["photo"]);

            $imageName = strtolower($empBindingModel->getFirstName()."_".
                $empBindingModel->getLastName()."_".
                $this->encryptionService->md5generator(time()));

            $imageForUpdate = $this->binaryImage->checkBinaryData($this->binaryImagesSentForUpload($empBindingModel, $imageName));

            $imageContainer = [];
            $oldImageToRemove = [];

            if ($imageForUpdate > 0) {
                if (array_key_exists("image_".$imageName,$imageForUpdate)) $oldImageToRemove[] = $oldImage;
                if (array_key_exists("avatar_".$imageName,$imageForUpdate)) $oldImageToRemove[] = $oldImageAv;
                if (array_key_exists("photo_".$imageName,$imageForUpdate)) $oldImageToRemove[] = $oldImagePh;

                $uploadedImages = $this->binaryImage->createImage($imageForUpdate, dirname(__DIR__)."\\webroot\\images\\");

                if (count($uploadedImages) != count($imageForUpdate)) {
                    $this->binaryImage->removeImage($uploadedImages, dirname(__DIR__)."\\webroot\\images\\");
                    return $this->dataReturn->errorResponse(400, "Image upload failed. Please try again.");
                }

                $imageContainer = $uploadedImages;
            }


            $empBindingModel->setImage(array_key_exists("image_".$imageName, $imageContainer) ? $imageContainer["image_".$imageName] : $oldImage);
            $empBindingModel->setAvatar(array_key_exists("avatar_".$imageName, $imageContainer) ? $imageContainer["avatar_".$imageName] : $oldImageAv);
            $empBindingModel->setPhoto(array_key_exists("photo_".$imageName, $imageContainer) ? $imageContainer["photo_".$imageName] : $oldImagePh);

            try {

                $this->employeeService->updEmp($empBindingModel);

            } catch (\Exception $e) {
                if (count($imageContainer) > 0) {
                    $this->binaryImage->removeImage($imageContainer,dirname(__DIR__)."\\webroot\\images\\");
                }
                return $this->dataReturn->errorResponse(400,$e->getMessage());
            }

            if (count($oldImageToRemove) > 0) {
                $this->binaryImage->removeImage($oldImageToRemove, dirname(__DIR__)."\\webroot\\images\\");
            }


            return $this->dataReturn->jsonData($this->employeeService->getEmp($empBindingModel->getId()));

        }


        return $this->dataReturn->errorResponse(401);
    }


    private function binaryImagesSentForUpload(EmpBindingModel $employeeBindingModel, $name)
    {
        $binaryImages = [];

        if ($employeeBindingModel->getImage()) {
            $binaryImages["image_" . $name] = $employeeBindingModel->getImage();
        }

        if ($employeeBindingModel->getAvatar()) {
            $binaryImages["avatar_" . $name] = $employeeBindingModel->getAvatar();
        }

        if ($employeeBindingModel->getPhoto()) {
            $binaryImages["photo_" . $name] = $employeeBindingModel->getPhoto();
        }

        return $binaryImages;
    }

    /**
     * @param  $newEmployeeId
     * @return \Employees\Models\DB\Email $email
     */
    private function emailEmployeePreparation($newEmployeeId)
    {
        $url = DefaultParam::UIRoot."employees/employee/".$newEmployeeId;
        /**
         * @var \Employees\Models\DB\Email $email
         */
        $email =  $this->employeeService->getEmailBodyForEmployeeCreation();

        $body = str_replace("%employee_link%", $url, $email->getBody());
        $altBody = str_replace("%employee_link%", $url, $email->getAltBody());

        $email->setBody($body);
        $email->setAltBody($altBody);
        $email->setIsHTML(true);

        return $email;
    }


}
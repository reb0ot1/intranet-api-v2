<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 17.6.2018 г.
 * Time: 23:29
 */


namespace Employees\Controllers;


use Employees\Config\DefaultParam;
use Employees\Models\Binding\Document\DocumentBindingModel;
use Employees\Core\DataReturnInterface;
use Employees\Core\MVC\FileUploadInterface;
use Employees\Models\DB\Document;
use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\DocumentCategoriesServiceInterface;
use Employees\Services\DocumentCategoryService;
use Employees\Services\DocumentServiceInterface;
use Employees\Services\EncryptionServiceInterface;


class NewslettersController
{

    private $encryptionService;

    private $authenticationService;

    private $dataReturn;

    private $fileUpload;

    private $documentService;

    private $categoriesService;

    public function __construct(EncryptionServiceInterface $encryptionService,
                                AuthenticationServiceInterface $authenticationService,
                                DataReturnInterface $dataReturn,
                                FileUploadInterface $fileUpload,
                                DocumentServiceInterface $documentService,
                                DocumentCategoriesServiceInterface $categoriesService
    )
    {
        $this->encryptionService = $encryptionService;

        $this->authenticationService = $authenticationService;

        $this->dataReturn = $dataReturn;

        $this->fileUpload = $fileUpload;

        $this->documentService = $documentService;

        $this->categoriesService = $categoriesService;
    }

    public function findall()
    {
        $newsletters = $this->documentService->all();

        return $this->dataReturn->jsonData($newsletters);

    }

    public function addDocument(DocumentBindingModel $documentBindingModel)
    {
        if ($this->authenticationService->isTokenCorrect()) {

            /**
             * @var Document $categories
             */

            $categories = $this->categoriesService->findOne($documentBindingModel->getCategoryId());

            if (!$categories) {
                return $this->dataReturn->errorResponse(400,"No such category");
            }

            if (count($this->fileUpload->getFiles()) == 0) {
                return $this->dataReturn->errorResponse(400, "No file was sent.");
            }

            if (!$this->fileUpload->validate(["pdf"])) {
                return $this->dataReturn->errorResponse(400, "Check your file extension and file size.");
            }

            try {

                $uploadedFiles = $this->fileUpload->upload($categories->getFolder());
                $documentBindingModel->setFiles($uploadedFiles);

                $documentId = $this->documentService->add($documentBindingModel);

                return $this->dataReturn->jsonData($this->documentService->findOne($documentId));

            } catch (\Exception $e) {
                return $this->dataReturn->errorResponse(400, $e->getMessage());
            }

        }

        return $this->dataReturn->errorResponse(401);
    }

    public function downloadNewsletter($filename = null)
    {
        $fileQuery = $this->documentService->findOne(55); //for testing purpose

        $file = dirname(__DIR__)."\webroot\documents\\newsletters\\".$fileQuery["name"];

        if (file_exists($file)) {
            try{
                $this->fileUpload->download($file);
                return true;
            } catch (\Exception $e){
                return $this->dataReturn->errorResponse(400,$e->getMessage());
            }
//            header("location:".DefaultParam::ServerRoot.$file);

        }

        return $this->dataReturn->errorResponse(400,"File not found");

    }

    public function remove($newsletterId)
    {

        $newsletterFolder = dirname(__DIR__)."\webroot\documents\\newsletters\\";
        $newsletter = $this->documentService->findOne($newsletterId);
        $newsletterFileName = $newsletter["name"];
        if (!$newsletter) {
            return $this->dataReturn->errorResponse(400,"Newsletter not found");
        }

        if ($this->documentService->remove($newsletterId)){
            if (file_exists($newsletterFolder.$newsletterFileName)) {
                $test = $this->fileUpload->remove("\documents\\newsletters\\", array($newsletterFileName));
            }

            return $this->dataReturn->jsonData(["id"=>$newsletterId]);
        }

        return $this->dataReturn->errorResponse(400,"The newsletter was not removed. Please try again");
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 26.11.2017 г.
 * Time: 12:14
 */

namespace Employees\Controllers;


use Employees\Core\DataReturnInterface;
use Employees\Models\Binding\News\NewsBindingModel;
use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\EmailServiceInterface;
use Employees\Services\EncryptionServiceInterface;
use Employees\Services\ImageFromBinServiceInterface;
use Employees\Services\NewsServiceInterface;
use Employees\Config\DefaultParam;

class NewsController
{
    private $newsService;
    private $encryptionService;
    private $authenticationService;
    private $binaryImage;
    private $dataReturn;
    private $emailService;

    public function __construct(NewsServiceInterface $newsService,
                                EncryptionServiceInterface $encryptionService,
                                AuthenticationServiceInterface $authenticationService,
                                ImageFromBinServiceInterface $imageFromBinService,
                                EmailServiceInterface $emailService,
                                DataReturnInterface $dataReturn)
    {
        $this->newsService = $newsService;
        $this->encryptionService = $encryptionService;
        $this->authenticationService = $authenticationService;
        $this->binaryImage = $imageFromBinService;
        $this->emailService = $emailService;
        $this->dataReturn = $dataReturn;
    }

    public function getNews()
    {

        $emailSend = $this->emailService->sendEmail();

        var_dump($emailSend);
        exit;

        $list = $this->newsService->getAllNews("yes");

        if (is_array($list)) {

            foreach ($list as $key => $value) {

                if (array_key_exists("image", $list[$key])) {
                    $list[$key]["image"] = DefaultParam::ServerRoot.DefaultParam::NewsImageContainer.$list[$key]["image"];
                }
            }
        }

//        var_dump($list);
//exit;
        return $this->dataReturn->jsonData($list);
    }

    public function addNews(NewsBindingModel $bindingModel)
    {

        if ($this->authenticationService->isTokenCorrect()) {

            $author = $this->authenticationService->getUserInfo();
            $now = date("d/m/y");

//            $bindingModel->setDate($now);
            //$bindingModel->setAuthor($author["first"] . " " . $author["last"]);
            $bindingModel->setAdminId($author["id"]);

            $md5string = $this->encryptionService->md5generator(time() . $bindingModel->getTitle() . $bindingModel->getBody());

//            if ($this->binaryImage->createImage($bindingModel->getImage(), DefaultParam::NewsImageContainer, $md5string, "png")) {
//
//                $bindingModel->setImage($md5string . ".png");

            if ($this->newsService->addNews($bindingModel, $md5string)) {
                $newsList = $this->newsService->getNewsByStrId($md5string);
//                $newsList["image"] = DefaultParam::ServerRoot . DefaultParam::NewsImageContainer . $newsList['image'];
                return $this->dataReturn->jsonData($newsList);

            } else {

//                $this->binaryImage->removeImage(DefaultParam::NewsImageContainer . $md5string . ".png");

                return $this->dataReturn->errorResponse(400, "Add news failed");
            }
//            } else {
//
//
//                return $this->dataReturn->errorResponse(400, "Image upload failed");
//            }
        }

        return $this->dataReturn->errorResponse(401);
    }

    public function updateNews($theId,NewsBindingModel $bindingModel)
    {


//        $str = '{"errors":[{"status": "404","title": "sklfksdkljfklsdf": "Cannot POST /posts"}]}';
//        http_response_code("304");
//        //print_r($str);
//        exit;
        if ($this->authenticationService->isTokenCorrect()) {

            $bindingModel->setId($theId);

//            var_dump($bindingModel->getDate());
//            $updatedDate = strtotime("+1 day", strtotime($bindingModel->getDate()));
//            $bindingModel->setDate(date("Y-m-d", $updatedDate));
//            $news = $this->newsService->getNews($theId);

//            $oldImage = $news["image"];
//
//            $isBinaryImage = preg_match("/^data:image\/(png|jpeg);base64,/", $bindingModel->getImage()) > 0 ? true : false;

//            if ($isBinaryImage) {

                $md5string = $this->encryptionService->md5generator(time() . $bindingModel->getTitle());

//                $this->binaryImage->createImage($bindingModel->getImage(), DefaultParam::NewsImageContainer, $md5string, "png");
//                $bindingModel->setImage($md5string . ".png");
//            } else {
//                $bindingModel->setImage($oldImage);
//            }

            if ($this->newsService->updateNews($bindingModel)) {

//                if ($isBinaryImage) {
//                    $this->binaryImage->removeImage(DefaultParam::NewsImageContainer . $oldImage);
//                }
                $updatedNews = $this->newsService->getNews($bindingModel->getId());
               // $updatedNews["image"] = DefaultParam::ServerRoot . DefaultParam::NewsImageContainer . $updatedNews["image"];
                return $this->dataReturn->jsonData($updatedNews);
            }

            return $this->dataReturn->errorResponse(400, "The news was not updated");
        }

        return $this->dataReturn->errorResponse(401);
    }


    public function deleteNews($newsId)
    {
        if ($this->authenticationService->isTokenCorrect()) {

            if ($this->newsService->removeNews($newsId)) {
                return $this->dataReturn->jsonData(["id"=>$newsId]);
            }
            else {
                return $this->dataReturn->errorResponse(400,"The news was not removed. Please try again");
            }
        }

        return $this->dataReturn->errorResponse(401);
    }

    public function uploadImage()
    {
        var_dump($_FILES);
    }

}
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
use Employees\Services\EmailService;
use Employees\Services\EmailServiceInterface;
use Employees\Services\EncryptionServiceInterface;
use Employees\Services\ImageFromBinServiceInterface;
use Employees\Services\NewsServiceInterface;
use Employees\Config\DefaultParam;
use Employees\Vendor\phpmailer\src\Exception;

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

            $bindingModel->setAdminId($author["id"]);

            $md5string = $this->encryptionService->md5generator(time() . $bindingModel->getTitle() . $bindingModel->getBody());

            try {
                $newArticleId = $this->newsService->addNews($bindingModel, $md5string);
                $article = $this->newsService->getNews($newArticleId);

                $email = new EmailService();

                $email->sendEmail($this->emailNewArticlePreparation($newArticleId));

                return $this->dataReturn->jsonData($article);

            } catch (Exception $e) {
                return $this->dataReturn->errorResponse(400, $e->getMessage());
            }
        }

        return $this->dataReturn->errorResponse(401);
    }

    public function updateNews($theId,NewsBindingModel $bindingModel)
    {

        if ($this->authenticationService->isTokenCorrect()) {

            $bindingModel->setId($theId);


                $md5string = $this->encryptionService->md5generator(time() . $bindingModel->getTitle());


            if ($this->newsService->updateNews($bindingModel)) {

                $updatedNews = $this->newsService->getNews($bindingModel->getId());
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

    private function emailNewArticlePreparation($articleId)
    {
        $url = "http://localhost:4200/articles/article/".$articleId;
        /**
         * @var \Employees\Models\DB\Email $email
         */
        $email =  $this->newsService->getEmailBodyForArticleCreation();

        $body = str_replace("%article_link%", $url, $email->getBody());
        $altBody = str_replace("%article_link%", $url, $email->getAltBody());

        $email->setBody($body);
        $email->setAltBody($altBody);
        $email->setIsHTML(true);

        return $email;
    }

}
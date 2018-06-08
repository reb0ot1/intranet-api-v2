<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 29.8.2017 г.
 * Time: 12:20
 */

namespace Employees\Core;



use Employees\Core\MVC\MVCContextInterface;


class DataReturn implements DataReturnInterface
{
    private $mvcContext;

    private $dataContainer;

    public function __construct(MVCContextInterface $MVCContext)
    {
        $this->mvcContext = $MVCContext;
    }

    public function jsonData($theData)
    {
        print_r(json_encode(array($this->mvcContext->getController() => $theData)));
    }

    public function json($theData)
    {
        print_r(json_encode($theData));
    }

    public function tokenReturn($token)
    {
        print_r(json_encode($token));
    }


    public function errorResponse($status, $message = null)
    {

        http_response_code($status);
        if ($message) {
            print_r('{"errors":[{"status": "'.$status.'","title": "'.$message.'": "Cannot POST /posts"}]}');
        }
    }

    public function successResponse($status, $message=null)
    {
        http_response_code($status);
        if ($message) {
            print_r('{"success":[{"status": "'.$status.'","title": "'.$message.'": "Feedback is sent"}]}');
        }
    }

    public function accessDenied($status, $message = null)
    {
        http_response_code($status);
        print_r(json_encode(array("access_token" => "", "errors" => array("status"=>$status, "title"=>$message, "message"=>"Cannot login!"))));
    }


}
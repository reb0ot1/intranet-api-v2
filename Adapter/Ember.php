<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 14.10.2017 г.
 * Time: 0:07
 */

namespace Employees\Adapter;


class Ember
{
    private static $methods = ["admin" =>
                                    array("GET"=>"", "POST"=>"token","PUT"=>"", "DELETE"=>"", "OPTIONS" => "option"),
                               "employees" =>
                                    array("GET"=>"list", "POST"=>"addemployee","PUT"=>"updateemployee", "DELETE"=>"removeemployee", "OPTIONS" => "option"),
                               "news" =>
                                    array("GET"=>"getNews", "POST" => "addnews", "PUT" => "updatenews", "DELETE"=>"deletenews", "OPTIONS" => "option"),
                                "benefits" =>
                                    array("GET"=>"list", "POST" => "", "PUT" => "", "DELETE"=>"", "OPTIONS" => "option"),
                                "files" =>
                                    array("GET"=>"list", "POST" => "", "PUT" => "", "DELETE"=>"", "OPTIONS" => "option"),
                                "feedbacks" =>
                                    array("GET"=>"testGet", "POST" => "sendfeedback", "PUT" => "", "DELETE"=>"", "OPTIONS" => "option"),
                                "settings" =>
                                    array("GET"=>"viewData", "POST" => "", "PUT" => "", "DELETE"=>"", "OPTIONS" => "option")
                                ];

    private $theController;

    private $theMethod;

    private $phpInput = array();

    public function __construct($controller, $method)
    {
        $this->theController = $controller;
        $this->theMethod = $method;
        $this->addDataToPOSTIfEmpty();
    }

    private function addDataToPOSTIfEmpty()
    {
        $json = json_decode(file_get_contents("php://input"), true);
        if ($json) {

                $this->phpInput = $json;

                if ($this->theController == "employees") {
                    $_POST = $this->phpInput["employee"];
                } else if ($this->getController() == "feedbacks") {
                    $_POST = $this->phpInput["feedback"];
                } else {
                    $_POST = $this->phpInput[$this->theController];
                }

        }
    }

    public function getController() {

        return $this->theController;

    }

    public function getMethod() {

        return self::$methods[$this->theController][$this->theMethod];
    }


}
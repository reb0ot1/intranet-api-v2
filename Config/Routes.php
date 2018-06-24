<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 7.6.2018 г.
 * Time: 0:04
 */

namespace Employees\Config;


class Routes
{

    static $GET = [
        "admin"=>["controller"=>"admin", "method"=>"","arguments"=>[]],
        "employees"=>["controller"=>"employees", "method"=>"find","arguments"=>[]],
        "news"=>["controller"=>"news", "method"=>"getNews","arguments"=>[]],
        "newsletters"=>["controller"=>"newsletters", "method"=>"findall","arguments"=>[]],
        "companies"=>["controller"=>"settings", "method"=>"getDropDownOptions","arguments"=>["companies"]],
        "positions"=>["controller"=>"settings", "method"=>"getDropDownOptions","arguments"=>["positions"]],
        "teams"=>["controller"=>"settings", "method"=>"getDropDownOptions","arguments"=>["teams"]],
        "benefits"=>["controller"=>"benefits", "method"=>"list","arguments"=>[]],
        "feedback"=>["controller"=>"feedback", "method"=>"testGet","arguments"=>[]]
    ];

    static $POST = [
        "admin"=>["controller"=>"admin", "method"=>"token","arguments"=>[]],
        "employees"=>["controller"=>"employees", "method"=>"addemployee","arguments"=>[]],
        "news"=>["controller"=>"news", "method"=>"addnews","arguments"=>[]],
        "imageupload"=>["controller"=>"files","method"=>"httpNewsImageUpload", "arguments"=>[]],
        "feedback"=>["controller"=>"feedback", "method"=>"sendfeedback","arguments"=>[]],
        "settings"=>["controller"=>"settings", "method"=>"","arguments"=>[]],
        "newsletters"=>["controller"=>"newsletters", "method"=>"addDocument","arguments"=>[]]
    ];

    static $PUT = [
        "admin"=>["controller"=>"admin", "method"=>"","arguments"=>[]],
        "employees"=>["controller"=>"employees", "method"=>"updateemployee","arguments"=>[]],
        "news"=>["controller"=>"news", "method"=>"updatenews","arguments"=>[]],
        "files"=>["controller"=>"files", "method"=>"","arguments"=>[]],
        "feedback"=>["controller"=>"feedback", "method"=>"","arguments"=>[]],
        "settings"=>["controller"=>"settings", "method"=>"","arguments"=>[]]
    ];

    static $DELETE = [
        "admin"=>["controller"=>"", "method"=>"","arguments"=>[]],
        "employees"=>["controller"=>"employees", "method"=>"removeemployee","arguments"=>[]],
        "news"=>["controller"=>"news", "method"=>"deletenews","arguments"=>[]],
        "files"=>["controller"=>"files", "method"=>"","arguments"=>[]],
        "feedback"=>["controller"=>"feedback", "method"=>"","arguments"=>[]],
        "settings"=>["controller"=>"settings", "method"=>"","arguments"=>[]]
    ];
}
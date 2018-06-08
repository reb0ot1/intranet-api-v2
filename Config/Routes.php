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
    static $GET = ["admin"=>"", "employees"=>"find", "news"=>"getNews", "benefits"=>"list", "files"=>"list", "feedback"=>"testGet", "settings"=>"viewData", "dropdownoptions"=>"allselectfields"];

    static $POST = ["admin"=>"token", "employees"=>"addemployee", "news"=>"addnews", "benefits"=>"", "files"=>"uploadfile", "feedback"=>"sendfeedback", "settings"=>""];

    static $PUT = ["admin"=>"", "employees"=>"updateemployee", "news"=>"updatenews", "benefits"=>"", "files"=>"", "feedback"=>"", "settings"=>""];

    static $DELETE = ["admin"=>"", "employees"=>"removeemployee", "news"=>"deletenews", "benefits"=>"", "files"=>"", "feedback"=>"", "settings"=>""];
}
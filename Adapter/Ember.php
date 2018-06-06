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
    private  $routes;

    private $controller;


    public function __construct($controller, $roots)
    {
        $this->controller = $controller;
        $this->routes = $roots;
    }


    public function getController()
    {

        return $this->controller;

    }

    public function getMethod()
    {
        if (array_key_exists($this->controller, $this->routes)) {
            return $this->routes[$this->controller];
        }

        return null;
    }


}
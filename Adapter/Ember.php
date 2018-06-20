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

    private $method;

    private $arguments;


    public function __construct($controller, $arguments, $roots)
    {
        $this->controller = $controller;
        $this->routes = $roots;
    }


    public function getController()
    {
        if (array_key_exists($this->controller, $this->routes)) {
            return $this->controller;
        }

        return null;
    }

    public function getMethod()
    {
        if (array_key_exists($this->controller, $this->routes)) {
            return $this->routes[$this->controller];
        }

        return null;
    }


}
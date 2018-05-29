<?php

namespace Employees\Services;

interface ResponseServiceInterface
{
    public function redirect($controller, $action, array $params = []);
}
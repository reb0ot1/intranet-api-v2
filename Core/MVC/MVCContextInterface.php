<?php

namespace Employees\Core\MVC;


interface MVCContextInterface
{
    public function getController() : string;

    public function getAction() : string;

    public function getUriJunk() : string;

    public function getArguments() : array;

}
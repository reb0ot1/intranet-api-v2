<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 9.6.2018 г.
 * Time: 20:44
 */

namespace Employees\Core\MVC;


interface FileUploadInterface
{
    public function upload($folder);

    public function getFiles();
}
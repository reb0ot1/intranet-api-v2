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

    public function remove($folder, $name);

    public function download($filename);

    public function validate($acceptableTypes, $sizeLimit = 10000000);

}
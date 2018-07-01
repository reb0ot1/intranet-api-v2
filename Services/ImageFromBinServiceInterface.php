<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 16.12.2017 г.
 * Time: 11:28
 */

namespace Employees\Services;


interface ImageFromBinServiceInterface
{
    public function checkImageType($data);

    public function checkBinaryData($binaryData);

    public function createImage($binaryData, $path);

    public function removeImage($imageNames, $path);

}
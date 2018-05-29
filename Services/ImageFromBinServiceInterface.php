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
    public function checkBinaryData($binaryData) : bool;

    public function createImage($binaryData, $path, $imageName, $imgType) : bool;

    public function removeImage($imagePath): bool;


}
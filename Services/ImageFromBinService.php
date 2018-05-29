<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 16.12.2017 г.
 * Time: 11:21
 */

namespace Employees\Services;


class ImageFromBinService implements ImageFromBinServiceInterface
{
    private $pattern = "/^data:image\/(png|jpeg);base64,/";

    private function getTheImageType($data) {

    }

    private function decodeBinary($binaryData)
    {
        $data = preg_replace($this->pattern, "", $binaryData);
        $data = base64_decode($data);

        return $data;
    }

    public function checkBinaryData($binaryData) : bool
    {
        $im = imagecreatefromstring($this->decodeBinary($binaryData));

        if ($im !== false) {
            return true;
        }

        return false;
    }

    public function createImage($binaryData, $path, $imageName, $imgType) : bool
    {

        $data = $this->decodeBinary($binaryData);
        if (strlen($data) > 0) {

            $im = imagecreatefromstring($data);

            if ($im !== false) {
                file_put_contents($path.$imageName.'.'.$imgType, $data);

                return true;
            }

        }
        
        return false;
    }

    public function removeImage($imagePath) : bool
    {
        return unlink($imagePath);
    }

}
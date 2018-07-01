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
    private $pattern = "/^data:image\/(png|jpeg|jpg);base64,/";

    private function decodeBinary($binaryData)
    {
        $data = preg_replace($this->pattern, "", $binaryData);
        $data = base64_decode($data);

        return $data;
    }

    private function getImageType($data)
    {
        preg_match($this->pattern, $data, $matches, PREG_OFFSET_CAPTURE);

        return array_pop($matches)[0];
    }

    public function checkImageType($data)
    {
        foreach($data as $binaryImage) {

            if (!preg_match($this->pattern, $binaryImage)) {
                return false;
            }
        }

        return true;
    }

    public function checkBinaryData($binaryData)
    {
        $imageBinaryData = [];
        foreach ($binaryData as $key => $value) {

//            $im = imagecreatefromstring($this->decodeBinary($value));

            if (preg_match($this->pattern, $value)) {
                $imageBinaryData[$key] = $value;
            }
        }

        return $imageBinaryData;
    }

    public function createImage($binaryData, $path)
    {
        $uploadedImages = [];
            foreach ($binaryData as $key=>$image) {
                $ext = $this->getImageType($image);
                $data = $this->decodeBinary($image);
                if (strlen($data) > 0) {
                    $imageName = $key.'.'.$ext;
                    file_put_contents($path.$imageName, $data);
                    $uploadedImages[$key] = $imageName;
                }
            }

        return $uploadedImages;
    }

    public function removeImage($imageNames, $path)
    {
        foreach ($imageNames as $image) {
            unlink($path.$image);
        }

        return true;
    }

}
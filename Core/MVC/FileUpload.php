<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 9.6.2018 г.
 * Time: 20:28
 */

namespace Employees\Core\MVC;


class FileUpload implements FileUploadInterface
{
    private $files;

    private $globalUploadFolder;

    public function __construct($files, $globalUploadFolder)
    {
        $this->files = $files;
        $this->globalUploadFolder = $globalUploadFolder;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    public function upload($folder)
    {
        $uploadDir = $this->globalUploadFolder.$folder."/";
        $uploadedFiles = [];

        foreach ($this->files as $file) {
            $name = $file['name'];

            $uploadFile = $uploadDir.$name;

            if (!move_uploaded_file($file['tmp_name'], $uploadFile)) {

                throw new \Exception("File was not uploaded");
            }

            $uploadedFiles[] = $name;
        }

        return  $uploadedFiles;

    }


    public function remove($folder, $files = [])
    {
        $fileFolder = $this->globalUploadFolder.$folder;
        $removedFiles = [];
        foreach ($files as $file) {
            $removedFiles[$file] = "notremoved";
            if (file_exists($fileFolder."/".$file)){
                unlink($fileFolder."/".$file);
                $removedFiles[$file] = "removed";
            }
        }

        return $removedFiles;
    }
}
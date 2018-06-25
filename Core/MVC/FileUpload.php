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
            $fileNameCheck = explode(".",$name);
            $type = $fileNameCheck[count($fileNameCheck)-1];
            $size = $file["size"];
            $filename = $fileNameCheck[0]."_".md5(time()).".".$type;
            $uploadFile = $uploadDir.$filename;

            if (!move_uploaded_file($file['tmp_name'], $uploadFile)) {

                throw new \Exception("File was not uploaded");
            }

            $uploadedFiles[] = array("name"=>$filename, "type"=>$type, "size"=>$size);
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

    public function download($filename)
    {
            $file = $filename;

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
    }


    public function validate($acceptableTypes, $sizeLimit = 10000000)
    {
        $limit = $sizeLimit;
        $acceptableFileTypes = $acceptableTypes;

        foreach ($this->getFiles() as $file) {
            $filename = explode(".",$file["name"]);
            $ext = array_pop($filename);
            if ($file["size"] > $limit ) {
                return false;
                break;
            }

            if (!in_array($ext, $acceptableFileTypes)){

                return false;
                break;
            }
        }

        return true;
    }
}
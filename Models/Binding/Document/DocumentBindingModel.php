<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 10.6.2018 г.
 * Time: 13:20
 */

namespace Employees\Models\Binding\Document;

class DocumentBindingModel
{
    private $categoryId;
    private $files = [];

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param array $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }
}
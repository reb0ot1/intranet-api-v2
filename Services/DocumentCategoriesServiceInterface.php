<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 10.6.2018 г.
 * Time: 16:02
 */

namespace Employees\Services;


interface DocumentCategoriesServiceInterface
{
    public function findAll();

    public function findOne($categoryId);
}
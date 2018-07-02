<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 10.6.2018 г.
 * Time: 13:31
 */

namespace Employees\Services;


interface DocumentServiceInterface
{
    public function all();

    public function findOne($documentId);

    public function add($documentBindingModel);

    public function remove($documentId);

    public function getEmailBodyForNewsletter();
}
<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 21.12.2017 г.
 * Time: 17:58
 */

namespace Employees\Core;


interface DataReturnInterface
{
    public function jsonData($theData);

    public function serializeObjectsToJson($objects);

    public function json($theData);

    public function tokenReturn($token);

    public function errorResponse($status, $message=null);

    public function successResponse($status, $message=null);

    public function accessDenied($status, $message = null);
}
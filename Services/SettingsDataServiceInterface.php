<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 2.6.2018 г.
 * Time: 21:31
 */

namespace Employees\Services;


interface SettingsDataServiceInterface
{
    public function companies();

    public function positions();

    public function teams();

    public function educations();

    public function hobbies();

}
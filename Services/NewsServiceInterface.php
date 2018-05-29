<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 26.11.2017 г.
 * Time: 12:46
 */

namespace Employees\Services;


use Employees\Models\Binding\News\NewsBindingModel;

interface NewsServiceInterface
{
    public function getAllNews($isActive);

    public function getNews($id);

    public function getNewsByStrId($uniqueStr) : array ;

    public function addNews(NewsBindingModel $newsBindingModel, $uniqueStr) : bool;

    public function updateNews(NewsBindingModel $bindingModel) : bool;

    public function removeNews($id);
}
<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 19.11.2017 г.
 * Time: 13:17
 */

namespace Employees\Core\MVC;


class KeyHolder implements KeyHolderInterface
{

    private $tokenKey;


    public function __construct($key)
    {
        $this->tokenKey = $key;
    }

    /**
     * @return mixed
     */
    public function getTokenKey()
    {
        return $this->tokenKey;
    }

    public function ifTokenSet()
    {
        if (strlen($this->tokenKey) > 0) {
            return true;
        }

        return false;
    }

}
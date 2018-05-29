<?php

namespace Employees\Models\View;

class UserProfileEditViewModel
{
    private $id;
    private $username;
    private $password;
    private $email;
    private $isForeignEdit;

    /**
     * UserProfileEditViewModel constructor.
     * @param $username
     * @param $password
     * @param $email
     * @param $birthday
     */
    public function __construct($id, $username, $password, $email, $isForeignEdit)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->isForeignEdit = $isForeignEdit;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }



    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getIsForeignEdit()
    {
        return $this->isForeignEdit;
    }

    /**
     * @param mixed $isForeignEdit
     */
    public function setIsForeignEdit($isForeignEdit)
    {
        $this->isForeignEdit = $isForeignEdit;
    }

    /**
     * @return mixed
     */



}
<?php

namespace Employees\Models\Binding\Feedback;

class FeedbackBindingModel
{
    private $id;
    private $email;
    private $feedbackString;

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
    public function getFeedbackString()
    {
        return $this->feedbackString;
    }

    /**
     * @param mixed $feedbackString
     */
    public function setFeedbackString($feedbackString)
    {
        $this->feedbackString = $feedbackString;
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 2.7.2018 г.
 * Time: 23:28
 */

namespace Employees\Models\DB;


class Email
{
    private $subject;

    private $body;

    private $altBody;

    private $isHTML;

    private $from;

    private $sendTo;

    private $replyTo;

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getAltBody()
    {
        return $this->altBody;
    }

    /**
     * @param mixed $altBody
     */
    public function setAltBody($altBody)
    {
        $this->altBody = $altBody;
    }

    /**
     * @return mixed
     */
    public function getisHTML()
    {
        return $this->isHTML;
    }

    /**
     * @param mixed $isHTML
     */
    public function setIsHTML($isHTML)
    {
        $this->isHTML = $isHTML;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getSendTo()
    {
        return $this->sendTo;
    }

    /**
     * @param mixed $sendTo
     */
    public function setSendTo($sendTo)
    {
        $this->sendTo = $sendTo;
    }

    /**
     * @return mixed
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @param mixed $replyTo
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
    }



}
<?php

namespace Mail;

class MailMessage
{
    protected $from;
    protected $reply;
    protected $to;
    protected $toName;
    protected $fromName=null;
    protected $bbc=array();
    protected $replyName=null;
    protected $subject=null;
    protected $body=null;

    /**
     * @param string $to
     *
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $toName
     *
     * @return $this
     */
    public function setToName($toName)
    {
        $this->toName = $toName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToName()
    {
        return $this->toName;
    }

    /**
     * @param null $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return null|string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return null|string
     */
    public function getSubject()
    {
        return $this->subject;
    }


    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @return mixed
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * @return mixed
     */
    public function getReplyName()
    {
        return $this->replyName;
    }

    /**
     * @param string $fromName
     *
     * @return $this
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
        return $this;
    }

    /**
     * @param string $reply
     *
     * @return $this
     */
    public function setReply($reply)
    {
        $this->reply = $reply;
        return $this;
    }

    /**
     * @param string $replyName
     *
     * @return $this
     */
    public function setReplyName($replyName)
    {
        $this->replyName = $replyName;
        return $this;
    }

    /**
     * @param string $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param array $bbc
     *
     * @return $this
     */
    public function setBbc(array $bbc)
    {
        $this->bbc = $bbc;
        return $this;
    }

    /**
     * @return array
     */
    public function getBbc()
    {
        return $this->bbc;
    }

    /**
     * @return bool
     */
    public function hasBbc()
    {
        return !empty($this->bbc);
    }

}

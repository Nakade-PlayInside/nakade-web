<?php

namespace Mail\Services;

use Zend\Mail\Message;
use Zend\Mime\Mime;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Mail\Transport;
use \RuntimeException;
use Zend\Mail\Transport\TransportInterface;

class MailMessageFactory implements FactoryInterface
{
    private $from;
    private $reply;
    private $to;
    private $toName;
    private $fromName=null;
    private $replyName=null;
    private $subject=null;
    private $body=null;

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
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|Message
     *
     * @throws RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');

        if (!isset($config['nakade_mail']['message'])) {
            throw new RuntimeException(
                'Mail message configuration not found.');
        }

        $msgConfig = $config['nakade_mail']['message'];

        if (!isset($msgConfig['from'])) {
            throw new RuntimeException(
                'Mail Message option "from" not found.');
        }

        $from = $msgConfig['from'];
        $this->setFrom($from);

        if (!isset($msgConfig['reply'])) {
            throw new RuntimeException(
                'Mail Message option "reply" not found.');
        }

        $reply = $msgConfig['reply'];
        $this->setReply($reply);

        //optional
        $name = null;
        if (isset($msgConfig['name'])) {
            $name = $msgConfig['name'];
            $this->setFromName($name);
        }

        //optional
        $replyName = null;
        if (isset($msgConfig['replyName'])) {
            $replyName = $msgConfig['replyName'];
            $this->setReplyName($replyName);
        }

        return $this;
    }

    /**
     * @return Message
     *
     * @throws \RuntimeException
     */
    public function getMessage()
    {
        $message = new Message();
        $message->setEncoding("UTF-8");

        $from = $this->getFrom();
        $fromName = $this->getFromName();
        $message->setFrom($from, $fromName);

        $reply =$this->getReply();
        $replyName =$this->getReplyName();
        $message->setReplyTo($reply, $replyName);

        $to = $this->getTo();
        if (empty($to)) {
            throw new RuntimeException(
                'Mail setTo missing.');
        }
        $toName = $this->getToName();
        $message->setTo($to, $toName);

        $subject = $this->getSubject();
        if (empty($subject)) {
            throw new RuntimeException(
                'Mail subject missing.');
        }
        $message->setSubject($subject);

        $body = $this->getBody();
        if (empty($body)) {
            throw new RuntimeException(
                'Mail body missing.');
        }

        $html = new \Zend\Mime\Part($body);
        $html->type = Mime::TYPE_HTML;
        $html->charset= "charset=UTF-8";

        $text = new \Zend\Mime\Part($body);
        $text->type = "text/plain";
        $text->charset= "charset=UTF-8";

        $content = new \Zend\Mime\Message;
        $content->setParts(array($html, $text));

        $message->setBody($content);


        return $message;
    }

}

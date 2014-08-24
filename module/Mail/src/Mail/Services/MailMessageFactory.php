<?php

namespace Mail\Services;

use Mail\MailMessage;
use Zend\Mail\Message;
use Zend\Mime\Mime;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail\Transport;
use Zend\Mime\Part;
use Zend\Mime\Message as Content;

class MailMessageFactory extends MailMessage implements FactoryInterface
{

    //todo: expand service if mails queue is a performance problem; put mails into a table and send it by cron job
    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|Message
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');

        if (!isset($config['nakade_mail']['message'])) {
            throw new \RuntimeException(
                'Mail message configuration not found.');
        }

        $msgConfig = $config['nakade_mail']['message'];

        if (!isset($msgConfig['from'])) {
            throw new \RuntimeException(
                'Mail Message option "from" not found.');
        }

        $from = $msgConfig['from'];
        $this->setFrom($from);

        if (!isset($msgConfig['reply'])) {
            throw new \RuntimeException(
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
            throw new \RuntimeException(
                'Mail setTo missing.');
        }
        $toName = $this->getToName();
        $message->setTo($to, $toName);

        if ($this->hasBbc()) {
            $bbc = $this->getBbc();
            $message->addBcc($bbc);
        }

        $subject = $this->getSubject();
        if (empty($subject)) {
            throw new \RuntimeException(
                'Mail subject missing.');
        }
        $message->setSubject($subject);

        $body = $this->getBody();
        if (empty($body)) {
            throw new \RuntimeException(
                'Mail body missing.');
        }

        $content = $this->getContent($body);
        $message->setBody($content);

        return $message;
    }

    private function getContent($body)
    {
        $temp = nl2br($body);
        $html = $this->getMimePart($temp, Mime::TYPE_HTML);
        $text = $this->getMimePart($body, "text/plain");

        $content = new Content();
        $content->setParts(array($html, $text));

        return $content;
    }

    private function getMimePart($body, $type)
    {
        $part = new Part($body);
        $part->type = $type;
        $part->charset= "charset=UTF-8";

        return $part;
    }


}

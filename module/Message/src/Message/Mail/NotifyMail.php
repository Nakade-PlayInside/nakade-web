<?php
namespace Message\Mail;

use Mail\NakadeMail;
use Mail\Services\MailMessageFactory;
use \Zend\Mail\Transport\TransportInterface;

/**
 * Class NotifyMail
 *
 * @package Message\Mail
 */
class NotifyMail extends NakadeMail
{
    private $url='http://www.nakade.de';
    /**
     * @param MailMessageFactory $mailService
     * @param TransportInterface $transport
     */
    public function __construct(MailMessageFactory $mailService, TransportInterface $transport)
    {
        $this->mailService = $mailService;
        $this->transport = $transport;

        $this->url = 'http://'. $_SERVER['HTTP_HOST'];
    }

    /**
     * @return string
     */
    public function getMailBody()
    {

        $message =
            $this->translate('You have got a new message in your mailbox at %URL%.') . ' ' .
            PHP_EOL .
            $this->translate('Please logIn at %URL% for reading.') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->getSignature()->getSignatureText();

        $this->makeReplacements($message);

        return $message;
    }

    /**
     * @param string &$message
     */
    private function makeReplacements(&$message)
    {
        $message = str_replace('%URL%', $this->getUrl(), $message);
    }

    /**
     * @return string
     */
    public  function getSubject()
    {
        $subject = $this->translate('New Message at %URL%');
        $this->makeReplacements($subject);

        return $subject;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

}
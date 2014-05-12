<?php
/**
 * User: Holger Maerz
 * Date: 16.04.14
 * Time: 11:47
 */

namespace Message\Mail;

use Mail\NakadeMail;
use Mail\Services\MailMessageFactory;
use \Zend\Mail\Transport\TransportInterface;

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
        return $this->translate('New Message at nakade.de');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

}
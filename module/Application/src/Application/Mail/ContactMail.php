<?php

namespace Application\Mail;

use Application\Entity\Contact;
use Mail\NakadeMail;
use Mail\Services\MailMessageFactory;
use \Zend\Mail\Transport\TransportInterface;

/**
 * Class ContactMail
 *
 * @package Application\Mail
 */
class ContactMail extends NakadeMail
{
    private $contact;

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
            $this->translate('Dear %NAME%') . ', ' . PHP_EOL . PHP_EOL .
            $this->translate('Thank you for contacting us at %URL%.') . ' ' .
            PHP_EOL .
            $this->translate('Your request is processed as soon as possible.') . PHP_EOL .
            PHP_EOL .
            $this->translate('Your email') . ':  %EMAIL%' . PHP_EOL .
            $this->translate('Your message') . ':  ' . '%MESSAGE%' .
            PHP_EOL . PHP_EOL . PHP_EOL .
            $this->getSignature()->getSignatureText();

        if (!is_null($this->getContact())) {
            $message = str_replace('%MESSAGE%', $this->getContact()->getMessage(), $message);
            $message = str_replace('%NAME%', $this->getContact()->getName(), $message);
            $message = str_replace('%EMAIL%', $this->getContact()->getEmail(), $message);
        }

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
        return $this->translate('Your Contact to nakade.de');
    }

    /**
     * @param Contact $contact
     *
     * @return $this
     */
    public function setContact(Contact $contact)
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @return Contact
     */
    public function getContact()
    {
        return $this->contact;
    }
}
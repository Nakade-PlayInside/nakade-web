<?php
/**
 * User: Holger Maerz
 * Date: 16.04.14
 * Time: 11:47
 */

namespace Mail;

use Nakade\Abstracts\AbstractTranslation;
use Mail\Services\MailMessageFactory;
use User\Entity\UserInterface;
use \Zend\Mail\Transport\TransportInterface;
use Mail\Services\MailSignatureService;

/**
 * abstract Class NakadeMail
 *
 * @package Mail
 */
abstract class NakadeMail extends AbstractTranslation implements NakadeMailInterface
{
    protected $mailService;
    protected $transport;
    protected $signatureService;
    protected $bbc;

    /**
     * @return string
     */
    abstract public function getSubject();

    /**
     * @return string
     */
    abstract public function getMailBody();

    /**
     * @param UserInterface $user
     *
     * @return bool
     *
     */
    public function sendMail(UserInterface $user)
    {
        $subject = $this->getSubject();
        $this->getMailService()->setSubject($subject);
        $this->getMailService()->setBody($this->getMailBody());

        $this->getMailService()->setTo($user->getEmail());
        $this->getMailService()->setToName($user->getName());

        if ($this->hasBbc()) {
            $bbc = $this->getBbc();
            $this->getMailService()->setBbc($bbc);
        }

        $message = $this->getMailService()->getMessage();

        $this->getTransport()->send($message);

        return true;
    }

    /**
     * @param MailMessageFactory $mailService
     *
     * @return $this;
     */
    public function setMailService(MailMessageFactory $mailService)
    {
        $this->mailService = $mailService;
        return $this;
    }

    /**
     * @return MailMessageFactory
     */
    public function getMailService()
    {
        return $this->mailService;
    }

    /**
     * @param TransportInterface $transport
     *
     * @return $this;
     */
    public function setTransport(TransportInterface $transport)
    {
        $this->transport = $transport;
        return $this;
    }

    /**
     * @return TransportInterface
     */
    public function getTransport()
    {
        return $this->transport;

    }

    /**
     * @return MailSignatureService
     */
    public function getSignature()
    {
        return $this->signatureService;
    }

    /**
     * @param MailSignatureService $signatureService
     *
     * @return $this
     */
    public function setSignature(MailSignatureService $signatureService)
    {
        $this->signatureService = $signatureService;
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
<?php
/**
 * User: Holger Maerz
 * Date: 16.04.14
 * Time: 11:47
 */

namespace Appointment\Mail;

use Nakade\Abstracts\AbstractTranslation;
use Mail\Services\MailMessageFactory;
use User\Entity\User;
use \Zend\Mail\Transport\TransportInterface;

/**
 * Class AppointmentMail
 *
 * @package Appointment\Mail
 */
abstract class AppointmentMail extends AbstractTranslation implements NakadeMailInterface
{
    protected $mailService;
    protected $transport;


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
    abstract public function getSubject();

    /**
     * @return string
     */
    abstract public function getMailBody();

    /**
     * @param User $user
     *
     * @return bool
     *
     */
    public function sendMail(User $user)
    {
        if (!$user->isActive()) {
            return false;
        }

        $subject = $this->getSubject();
        $this->getMailService()->setSubject($subject);
        $this->getMailService()->setBody($this->getMailBody());

        $this->getMailService()->setTo($user->getEmail());
        $this->getMailService()->setToName($user->getName());

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


}
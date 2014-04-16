<?php
/**
 * User: Holger Maerz
 * Date: 16.04.14
 * Time: 11:47
 */

namespace Message\Notify;

use Nakade\Abstracts\AbstractTranslation;
use Mail\Services\MailMessageFactory;
use User\Entity\User;
use \Zend\Mail\Transport\TransportInterface;

class NotifyMail extends AbstractTranslation
{
    private $mailService;
    private $transport;


    /**
     * @param MailMessageFactory $mailService
     * @param TransportInterface $transport
     */
    public function __construct(MailMessageFactory $mailService, TransportInterface $transport)
    {
        $this->mailService = $mailService;
        $this->transport = $transport;
    }

    private function getMessage()
    {
        $message =
            $this->translate('You have got a new message in your mailbox at nakade.de.') . ' ' .
            $this->translate('Please logIn at nakade.de for reading.') . ' ' .
            $this->translate('Your Nakade Team');

        $message = str_replace("\n.", "\n..", $message);

        return $message;
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * todo: configure email sending in profile
     */
    public function sendMail(User $user)
    {
        if (!$user->isActive()) {
            return false;
        }

        $subject = $this->translate('New Message at nakade.de');
        $this->mailService->setSubject($subject);
        $this->mailService->setBody($this->getMessage());

        $this->mailService->setTo($user->getEmail());
        $this->mailService->setToName($user->getName());

        $message = $this->mailService->getMessage();

        $this->transport->send($message);
        return true;
    }
}
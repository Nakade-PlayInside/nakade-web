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

class ResponderMail extends AbstractTranslation
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
            $this->translate('A new appointment for your match date at nakade.de. was provided.') . ' ' .

            $this->translate('Your match: %black - %white') . ' ' .
            $this->translate('Actual match date: %date') . ' ' .
            $this->translate('New match date: %date') . ' ' .

            $this->translate('You have to confirm the appointment for updating.') . ' ' .
            $this->translate('Therefore, you just can click on the link provided below.') . ' ' .
            $this->translate('Or you login at nakade.de and confirm the new date on your site.') . ' ' .

            $this->translate('Otherwise, if you do NOT AGREE to the appointment, you can reject it.') . ' ' .
            $this->translate('For rejecting, you have to logIn at nakade.de and provide a reason.') . ' ' .

            $this->translate('After %TIME hours, the new appointment will be automatically confirmed.') . ' ' .
            $this->translate('In any case, after confirming the new date is binding.') . ' ' .
            $this->translate('Your Nakade Team');

        $message = str_replace("\n.", "\n..", $message);

        return $message;
    }

    /**
     * @return string
     */
    private function getSubject()
    {
        return $this->translate('Confirm your League Appointment at nakade.de');
    }

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
        $this->mailService->setSubject($subject);
        $this->mailService->setBody($this->getMessage());

        $this->mailService->setTo($user->getEmail());
        $this->mailService->setToName($user->getName());

        $message = $this->mailService->getMessage();

        $this->transport->send($message);
        return true;
    }
}
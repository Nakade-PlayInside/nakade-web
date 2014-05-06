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

class RejectMail extends AbstractTranslation
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
            $this->translate('The new appointment for your match date at nakade.de. is rejected.') . ' ' .
            $this->translate('Your match: %black - %white') . ' ' .
            $this->translate('Actual match date: %date') . ' ' .
            $this->translate('Proposed appointment: %date') . ' ' .

            $this->translate('A league manager will contact you as soon as possible.') . ' ' .

            $this->translate('Your Nakade Team');

        $message = str_replace("\n.", "\n..", $message);

        return $message;
    }

    /**
     * @return string
     */
    private function getSubject()
    {
        return $this->translate('Your League Appointment is rejected');
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
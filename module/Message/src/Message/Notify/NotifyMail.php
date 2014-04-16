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
    const SUBJECT = 'Nakade Message';


    /**
     * @param MailMessageFactory $mailService
     * @param TransportInterface $transport
     */
    public function __construct(MailMessageFactory $mailService, TransportInterface $transport)
    {
        $this->mailService = $mailService;
        $this->transport = $transport;

        $subject = $this->translate(self::SUBJECT);
        $this->mailService->setSubject($subject);
        $this->mailService->setBody($this->getMessage());
    }

    private function getMessage()
    {
        return
            $this->translate('You have got a new message in your mailbox at nakade.de.') .
            $this->translate('Please logIn for reading.') . '\n\n' .
            $this->translate('Your Nakade Team');
    }

    /**
     * @param User $sendTo
     *
     * @return bool
     */
    public function sendMail(User $sendTo)
    {
        $this->mailService->setTo($sendTo->getEmail());
        $this->mailService->setToName($sendTo->getName());

        $message = $this->mailService->getMessage();

        $this->transport->send($message);
        return true;
    }
}
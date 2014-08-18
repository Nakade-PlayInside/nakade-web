<?php
namespace Moderator\Mail;

/**
 * info for moderators to a new ticket
 *
 * @package Moderator\Mail
 */
class TicketMail extends SupportMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate('Dear %MODERATOR%') . ', ' .
            PHP_EOL . PHP_EOL .
            $this->translate('The ticket #%TICKET_NUMBER% is waiting for process.') . ' ' .
            PHP_EOL .
            $this->translate('Please logIn at %URL% as soon as possible.') .
            PHP_EOL . PHP_EOL .
            $this->getSignature()->getSignatureText();

        $this->makeReplacements($message);

        return $message;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        $subject = $this->translate('Ticket #%TICKET_NUMBER% in Process');
        $this->makeReplacements($subject);

        return $subject;
    }


}
<?php
namespace Support\Mail;

/**
 * info for requester about support ticket in process
 *
 * @package Support\Mail
 */
class ReplyInfoMail extends SupportMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate('Your support request needs your reply for processing.') .
            PHP_EOL .
            $this->translate('Please logIn at %URL% and have a look to your open support tickets as soon as possible.') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate('Your ticket: #%TICKET_NUMBER%') .
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
        $subject = $this->translate('Your Support Ticket #%TICKET_NUMBER%');
        $this->makeReplacements($subject);

        return $subject;
    }


}
<?php
namespace Support\Mail;

/**
 * info for requester about changed stage
 *
 * @package Support\Mail
 */
class StageChangedMail extends SupportMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate('Your support request was processed.') . ' ' .
            PHP_EOL .
            $this->translate('The new stage of your ticket #%TICKET_NUMBER% is "%STAGE%".') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate('For more information logIn at %URL%.') . ' ' .
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
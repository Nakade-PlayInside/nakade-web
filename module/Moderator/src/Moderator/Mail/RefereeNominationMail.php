<?php

namespace Moderator\Mail;

/**
 * Class RefereeNominationMail
 *
 * @package Moderator\Mail
 */
class RefereeNominationMail extends NominationMail
{
    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate('Dear %NAME%') . ', ' .
            PHP_EOL . PHP_EOL .
            $this->translate('You are nominated being a member of the arbitrating body.') .
            PHP_EOL .
            $this->translate('Please contact other referees in case of making decisions.') . ' ' .
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
        $subject = $this->translate('Your Nomination as Referee');
        $this->makeReplacements($subject);

        return $subject;
    }


}
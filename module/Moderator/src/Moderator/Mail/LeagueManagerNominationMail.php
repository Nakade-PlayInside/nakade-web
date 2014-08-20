<?php

namespace Moderator\Mail;

/**
 * Class RefereeNominationMail
 *
 * @package Moderator\Mail
 */
class LeagueManagerNominationMail extends NominationMail
{
    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate('Dear %NAME%') . ', ' .
            PHP_EOL . PHP_EOL .
            $this->translate('You are nominated as League Manager of %ASSOCIATION% by %OWNER%.') .
            PHP_EOL . PHP_EOL .
            $this->translate('Your task is to support participants of %ASSOCIATION%.') .
            PHP_EOL .
            $this->translate('Therefore, you find your tickets in the moderator flag.') . ' ' .
            $this->translate('On request, you can edit results or making appointments.') .
            PHP_EOL . PHP_EOL .
            $this->translate('It is advisable to reassure the request is wanted by both players.') .
            PHP_EOL .
            $this->translate('Remember to set a solved ticket to "Done".') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate('Thank you for your commitment.') . ' ' .
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
        $subject = $this->translate('Your Nomination as League Manager of %ASSOCIATION%');
        $this->makeReplacements($subject);

        return $subject;
    }


}
<?php
namespace League\Mail;

/**
 * mail for both players before a match as reminder
 *
 * @package League\Mail
 */
class MatchReminderMail extends LeagueMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        //todo: email body
        $message =
            $this->translate('This is a reminder of your next match at %NEW_DATE% .') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate('Your match: %MATCH_INFO%') .
            PHP_EOL . PHP_EOL .
            $this->translate('For any more details visit us at %URL%') . ' ' .
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
        return $this->translate('Your Match Reminder');
    }


}
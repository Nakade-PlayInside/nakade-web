<?php
namespace League\Mail;

/**
 * mail for both players to remind on open result
 *
 * @package League\Mail
 */
class ResultReminderMail extends LeagueMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate('Your match result is overdue.') . ' ' .
            $this->translate('Please provide your result immediately at %URL%.') .
            PHP_EOL . PHP_EOL .
            $this->translate('Your match: %MATCH_INFO%') .
            PHP_EOL .
            PHP_EOL .
            $this->translate('Match date: %NEW_DATE%') .
            PHP_EOL . PHP_EOL .
            $this->translate('According to our rules, a match is suspended if no result is provided within the next 3 days.') . ' ' .
            $this->translate('In case of a technical problem, please contact a league manager.') .
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
        return $this->translate('Your Match Result is Missing');
    }


}
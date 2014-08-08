<?php
namespace League\Mail;

/**
 * mail for both players before a match as reminder
 *
 * @package League\Mail
 */
class AppointmentReminderMail extends LeagueMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate('This is a reminder of your next match at %NEW_DATE% .') .
            PHP_EOL .
            $this->translate('Just in case, making an appointment is limited to 3 days before a match.') . ' ' .
            $this->translate('If you cannot make it on %NEW_DATE%, use the option of making an appointment on %URL%.') .
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
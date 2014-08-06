<?php
namespace Appointment\Mail;

/**
 * Class InfoMail
 *
 * @package Appointment\Mail
 */
class InfoMail extends AppointmentMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate('On request a moderator set an appointment for a new match date at %URL%.') .
            PHP_EOL . PHP_EOL .
            $this->translate('Your match: %MATCH_INFO%') .
            PHP_EOL .
            $this->translate('Actual match date: %OLD_DATE%') .
            PHP_EOL .
            $this->translate('New match date: %NEW_DATE%') .
            PHP_EOL . PHP_EOL .
            $this->translate('The new date is binding even without confirming.') .
            PHP_EOL .
            $this->translate('Make sure to update your iCal.') .
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
        return $this->translate('Appointment Reminder');
    }


}
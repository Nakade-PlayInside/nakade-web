<?php
/**
 * User: Holger Maerz
 * Date: 16.04.14
 * Time: 11:47
 */

namespace Appointment\Mail;

/**
 * mail for the submitter of an appointment
 *
 * @package Appointment\Mail
 */
class SubmitterMail extends AppointmentMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate('You submitted an appointment for a new match date at %URL%.') .
            PHP_EOL . PHP_EOL .
            $this->translate('Your match: %MATCH_INFO%') .
            PHP_EOL .
            $this->translate('Actual match date: %OLD_DATE%') .
            PHP_EOL .
            $this->translate('New match date: %NEW_DATE%') .
            PHP_EOL . PHP_EOL .
            $this->translate('Before updating, your opponent has to confirm the appointment .') .
            PHP_EOL .
            $this->translate('The confirmation process can last up to %TIME% hours.') .
            PHP_EOL .
            $this->translate('Do not update your iCal before confirmation.') .
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
        return $this->translate('New League Appointment Reminder');
    }


}
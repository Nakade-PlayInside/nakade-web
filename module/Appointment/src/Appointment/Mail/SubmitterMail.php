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
            $this->translate('You made an appointment for a new match date at nakade.de.') . ' ' .
            $this->translate('Your match: %black - %white') . ' ' .
            $this->translate('Actual match date: %date') . ' ' .
            $this->translate('New match date: %date') . ' ' .
            $this->translate('After your opponent confirmed your appointment, the new match is updated.') . ' ' .
            $this->translate('Do not update your iCal before confirmation.') . ' ' .
            $this->translate('Your Nakade Team');

        $message = str_replace("\n.", "\n..", $message);

        return $message;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->translate('New League Appointment at nakade.de');
    }


}
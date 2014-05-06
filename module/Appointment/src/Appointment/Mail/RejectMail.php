<?php
/**
 * User: Holger Maerz
 * Date: 16.04.14
 * Time: 11:47
 */

namespace Appointment\Mail;

/**
 * mail for both players and league manager
 *
 * @package Appointment\Mail
 */
class RejectMail extends AppointmentMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate('The new appointment for your match date at nakade.de. is rejected.') . ' ' .
            $this->translate('Your match: %black - %white') . ' ' .
            $this->translate('Actual match date: %date') . ' ' .
            $this->translate('Proposed appointment: %date') . ' ' .

            $this->translate('A league manager will contact you as soon as possible.') . ' ' .

            $this->translate('Your Nakade Team');

        $message = str_replace("\n.", "\n..", $message);

        return $message;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->translate('Your League Appointment is rejected');
    }


}
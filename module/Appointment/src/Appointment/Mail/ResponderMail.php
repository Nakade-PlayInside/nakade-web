<?php
/**
 * User: Holger Maerz
 * Date: 16.04.14
 * Time: 11:47
 */

namespace Appointment\Mail;

/**
 * mail for the responder of an appointment
 *
 * @package Appointment\Mail
 */
class ResponderMail extends AppointmentMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate('A new appointment for your match date at nakade.de. was provided.') . ' ' .

            $this->translate('Your match: %black - %white') . ' ' .
            $this->translate('Actual match date: %date') . ' ' .
            $this->translate('New match date: %date') . ' ' .

            $this->translate('You have to confirm the appointment for updating.') . ' ' .
            $this->translate('Therefore, you just can click on the link provided below.') . ' ' .
            $this->translate('Or you login at nakade.de and confirm the new date on your site.') . ' ' .

            $this->translate('Otherwise, if you do NOT AGREE to the appointment, you can reject it.') . ' ' .
            $this->translate('For rejecting, you have to logIn at nakade.de and provide a reason.') . ' ' .

            $this->translate('After %TIME hours, the new appointment will be automatically confirmed.') . ' ' .
            $this->translate('In any case, after confirming the new date is binding.') . ' ' .
            $this->translate('Your Nakade Team');

        $message = str_replace("\n.", "\n..", $message);

        return $message;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->translate('Confirm your League Appointment at nakade.de');
    }

}
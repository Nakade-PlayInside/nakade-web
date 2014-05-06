<?php
/**
 * User: Holger Maerz
 * Date: 16.04.14
 * Time: 11:47
 */

namespace Appointment\Mail;

/**
 * mail for both players after confirming
 *
 * @package Appointment\Mail
 */
class ConfirmMail extends AppointmentMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate('The new appointment for your match date at nakade.de. is confirmed.') .
            PHP_EOL . PHP_EOL .
            $this->translate('Your match: %black - %white') .
            PHP_EOL .
            $this->translate('New match date: %date') .
            PHP_EOL . PHP_EOL .
            $this->translate('If you use a calendar application, do not forget to update.') . ' ' .
            $this->translate('An updated iCal is found on your site after login at nakade.de.') .
            PHP_EOL . PHP_EOL .
            $this->translate('Your Nakade Team');

        return $message;
    }

    /**
     * @return string
     */
    public  function getSubject()
    {
        return $this->translate('Your League Appointment is confirmed');
    }

}
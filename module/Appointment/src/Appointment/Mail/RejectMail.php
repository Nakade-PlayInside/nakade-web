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
            $this->translate('The new appointment for your match date at %URL% is rejected.') .
            PHP_EOL . PHP_EOL .
            $this->translate('Your match: %MATCH_INFO%') .
            PHP_EOL .
            $this->translate('Actual match date: %OLD_DATE%') .
            PHP_EOL .
            $this->translate('Proposed appointment: %NEW_DATE%') .
            PHP_EOL . PHP_EOL .
            $this->translate('A league manager will contact you as soon as possible.') .
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
        return $this->translate('Your League Appointment is rejected');
    }


}
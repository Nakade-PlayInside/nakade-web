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
            $this->translate('The new appointment for your match date at %URL% is submitted.') .
            PHP_EOL . PHP_EOL .
            $this->translate('Your match: %MATCH_INFO%') .
            PHP_EOL .
            $this->translate('Actual match date: %OLD_DATE%') .
            PHP_EOL .
            $this->translate('New match date: %NEW_DATE%') .
            PHP_EOL . PHP_EOL .
            $this->translate('You have to confirm the appointment before updating.') .
            PHP_EOL .
            $this->translate('Therefore, you just can click on the link provided below.') .
            PHP_EOL . PHP_EOL . '%CONFIRM_LINK%' . PHP_EOL . PHP_EOL .
            $this->translate('Or you just login at %URL% and confirm the new date on your site.') .
            PHP_EOL .
            $this->translate('Otherwise, if you do NOT AGREE to the appointment, you can reject it.') .
            PHP_EOL .
            $this->translate('For rejecting, you have to logIn at %URL% and provide a reason.') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate('After %TIME% hours, the new appointment will be automatically confirmed.') .
            PHP_EOL .
            $this->translate('In any case, the new date is binding after confirming!') . ' ' .
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
        return $this->translate('Confirm your League Appointment at nakade.de');
    }

}
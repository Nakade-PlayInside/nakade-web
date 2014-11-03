<?php
namespace User\Mail;

/**
 * Class ClosedBetaRegistrationMail
 *
 * @package User\Mail
 */
class ClosedBetaRegistrationMail extends UserMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate("Welcome %FIRST_NAME%") . ', ' .
            PHP_EOL . PHP_EOL .
            $this->translate('Thank you for your registration at %URL%.') . ' ' .
            $this->translate('We are happy to welcome you in our community and hope you enjoy your time with us.') .
            PHP_EOL . PHP_EOL .
            $this->translate("Your Credentials") . ': ' . PHP_EOL . PHP_EOL .
            $this->translate("username") . ': ' . '%USERNAME%' . PHP_EOL .
            $this->translate("password") . ': ' . '%PASSWORD%' . PHP_EOL .
            PHP_EOL . PHP_EOL .
            $this->translate('Use your credentials for logIn.') .
            $this->translate('To change the generated password, go to your profile.') . ' ' . PHP_EOL .
            PHP_EOL . PHP_EOL .
            $this->translate('In case of a problem, please contact us.') . PHP_EOL . PHP_EOL .
            $this->getSignature()->getSignatureText();

        $this->makeReplacements($message);

        return $message;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        $subject = $this->translate('Your Registration at  %URL%');
        $this->makeReplacements($subject);

        return $subject;
    }


}
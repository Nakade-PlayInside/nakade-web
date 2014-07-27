<?php
namespace User\Mail;

/**
 * Class RegistrationMail
 *
 * @package User\Mail
 */
class RegistrationMail extends UserMail
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
            $this->translate('Before you can start, you have to confirm your email first.') . ' ' . PHP_EOL .
            $this->translate('Therefore, please click on the link below for confirmation.') . ' ' .
            $this->translate('This is necessary to activate your account, too.') .
            PHP_EOL . PHP_EOL . '%VERIFY_LINK%' . PHP_EOL . PHP_EOL .
            $this->translate("For security reasons, the validity of the link will expire after %DUE_DATE%.") . ' ' .
            $this->translate("If you failed to confirm in time, your account is deleted.") . ' ' .
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
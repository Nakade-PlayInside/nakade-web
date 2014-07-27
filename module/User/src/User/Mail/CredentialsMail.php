<?php
namespace User\Mail;

/**
 * Class CredentialsMail
 *
 * @package User\Mail
 */
class CredentialsMail extends UserMail
{
    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate("Hi %FIRST_NAME%") . ', ' .
            PHP_EOL . PHP_EOL .
            $this->translate('Your password was reset at %URL%.') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate("Your Credentials") . ': ' . PHP_EOL . PHP_EOL .
            $this->translate("username") . ': ' . '%USERNAME%' . PHP_EOL .
            $this->translate("password") . ': ' . '%PASSWORD%' . PHP_EOL .
            PHP_EOL . PHP_EOL .
            $this->translate("For security reasons, change the generated password as soon as possible.") . ' ' .
            $this->translate("Therefore, logIn and go to your profile.") . ' ' .
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
        $subject = $this->translate('Your New Password at  %URL%');
        $this->makeReplacements($subject);

        return $subject;
    }


}
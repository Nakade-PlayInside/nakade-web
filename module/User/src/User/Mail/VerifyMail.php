<?php
namespace User\Mail;

/**
 * Mail to verify a new email
 *
 * @package User\Mail
 */
class VerifyMail extends UserMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate("Hi %FIRST_NAME%") . ', ' .
            PHP_EOL . PHP_EOL .
            $this->translate('You have provided a new email address at %URL%.') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate('Before activating your new address, you have to confirm your email first.') . ' ' . PHP_EOL .
            $this->translate('Therefore, please click on the link below for confirmation.') . ' ' .
            $this->translate('This is necessary to activate your account, too.') .
            PHP_EOL . PHP_EOL . '%VERIFY_LINK%' . PHP_EOL . PHP_EOL .
            $this->translate("For security reasons, the validity of the link will expire after %DUE_DATE%.") . ' ' .
            $this->translate("If you failed to confirm in time, your account is deactivated.") . ' ' .
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
        $subject = $this->translate('Confirm Your Mail Address');
        return $subject;
    }


}
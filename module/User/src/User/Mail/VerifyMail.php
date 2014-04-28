<?php
namespace User\Mail;

use Zend\Mail\Transport\TransportInterface;
use Mail\Services\MailMessageFactory;

/**
 * Class VerifyMail
 *
 * Used for sending a verification mail to the registered user.
 * Mail has an expiration date and contents credentials and an
 * activation link. Mail is translated.
 *
 * @package User\Mail
 */
class VerifyMail extends UserMail
{

    /**
     * properties
     * @var mixed
     */

    protected $expire='72';
    protected $prefix = 'Nakade';
    protected $signature='Nakade Team';
    protected $club='Berliner Baduk Club e.V.';
    protected $register_court='Berlin-Charlottenburg';
    protected $register_no='VR31852';


    /**
     * Translated mail template. Needed for Poedit usage.
     *
     * @return array
     */
    public function getTranslatedMailTemplate()
    {
        //just for translation using PoE
        $salutation = sprintf("%s,\n\n%s\n\n",
            $this->translate("Welcome %firstname%"),
            $this->translate("Thank you for your registration at nakade.de")
        );

        $account = sprintf("%s:\n\n%s: %%username%%\n%s: %%password%%\n\n",
            $this->translate("Your Credentials"),
            $this->translate("username"),
            $this->translate("password")
        );

        $verify = sprintf("\n%s %s %s\n",
            $this->translate("Your account requires activation during the next %expire% hours."),
            $this->translate("Please click on the link for activation."),
            $this->translate("If you fail to activate your account in time, you have to reset your password using the 'forgot password' option.")
        );

        $greeting = sprintf("\n%s\n\n%s\n",
            $this->translate("May the stones be with you."),
            $this->translate("Your %signature%.")
        );

        $contact = sprintf("\n\n%%club%%\n%s: %%register_court%%\n%s: %%register_no%%",
            $this->translate("Court of Registration"),
            $this->translate("Register No.")
        );

        $subject = $this->translate("%prefix% - Your Credentials");
        $this->mailTemplates[self::SUBJECT] = $subject;

        $template = array(
            self::SALUTATION => $salutation,
            self::ACCOUNT    => $account,
            self::VERIFY     => $verify,
            self::GREETING   => $greeting,
            self::CONTACT    => $contact,
            self::LINK       => "\n\n%activationLink%\n\n",
            self::SUBJECT    => $subject,
        );

        return $template;

    }
}


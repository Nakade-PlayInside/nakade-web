<?php
namespace User\Mail;

/**
 * New verification mail after resetting the user's password.
 * Same procedure as in VerifyMail just with minor changes in the text.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class PasswordMail extends VerifyMail {
   
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
            $this->translate("Your password at nakade.de was reset.")
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
        
        $subject = $this->translate("%prefix% - Your New Credentials");
        $this->_mailTemplates[self::SUBJECT] = $subject;
        
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

?>

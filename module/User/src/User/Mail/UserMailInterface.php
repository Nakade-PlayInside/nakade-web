<?php
namespace User\Mail;

/**
 * Class VerifyMail
 * Used for sending a verification mail to the registered user.
 * Mail has an expiration date and contents credentials and an
 * activation link. Mail is translated.
 *
 * @package User\Mail
 */
Interface UserMailInterface
{

    /**
     * mail template keys as constants
     */
    const SALUTATION   = 'emailSalutation';
    const ACCOUNT      = 'emailAccount';
    const VERIFY       = 'emailVerify';
    const GREETING     = 'emailGreeting';
    const CONTACT      = 'emailContact';
    const LINK         = 'emailActivation';
    const SUBJECT      = 'emailSubject';



    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data);

    /**
     * @return string
     */
    public function getSubject();

    /**
     * @return string
     */
    public function getBody();

    /**
     * @return string
     */
    public function getTranslatedMailTemplate();
}


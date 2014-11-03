<?php

namespace User\Services;

use Mail\Services\AbstractMailService;
use User\Mail;
/**
 * Class MailService
 *
 * @package User\Services
 */
class MailService extends AbstractMailService
{

    const CREDENTIALS_MAIL = 'credentials';
    const REGISTRATION_MAIL = 'registration';
    const CLOSED_BETA_REGISTRATION_MAIL = 'closed_beta';
    const VERIFY_MAIL = 'verify';
    const COUPON_MAIL = 'coupon';

    /**
     * @param string $typ
     *
     * @return \User\Mail\UserMail
     *
     * @throws \RuntimeException
     */
    public function getMail($typ)
    {
        switch (strtolower($typ)) {

            case self::CREDENTIALS_MAIL:
                $mail = new Mail\CredentialsMail($this->getMessage(), $this->getTransport());
                break;

            case self::REGISTRATION_MAIL:
                $mail = new Mail\RegistrationMail($this->getMessage(), $this->getTransport());
                break;

            case self::CLOSED_BETA_REGISTRATION_MAIL:
                $mail = new Mail\ClosedBetaRegistrationMail($this->getMessage(), $this->getTransport());
                break;

            case self::VERIFY_MAIL:
                $mail = new Mail\VerifyMail($this->getMessage(), $this->getTransport());
                break;

            case self::COUPON_MAIL:
                $mail = new Mail\CouponMail($this->getMessage(), $this->getTransport());
                break;

            default:
                throw new \RuntimeException(
                    sprintf('An unknown mail type was provided.')
                );
        }

        $mail->setTranslator($this->getTranslator());
        $mail->setTranslatorTextDomain($this->getTextDomain());
        $mail->setSignature($this->getSignature());
        return $mail;
    }

    /**
     * @return mixed
     */
    public function getTextDomain()
    {
        if (is_null($this->textDomain)) {
            $config  = $this->getConfig();
            if (isset($config['User']['text_domain'])) {
                $this->textDomain = $config['User']['text_domain'];
            }
        }
        return $this->textDomain;
    }




}

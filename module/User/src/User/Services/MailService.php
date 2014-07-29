<?php

namespace User\Services;

use Nakade\MailServiceInterface;
use User\Mail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nakade\Abstracts\AbstractTranslation;
/**
 * Class MailService
 *
 * @package User\Services
 */
class MailService extends AbstractTranslation implements FactoryInterface, MailServiceInterface
{

    const CREDENTIALS_MAIL = 'credentials';
    const REGISTRATION_MAIL = 'registration';
    const VERIFY_MAIL = 'verify';
    const COUPON_MAIL = 'coupon';

    private $transport;
    private $message;
    private $signature;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Zend\ServiceManager\FactoryInterface
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $this->transport = $services->get('Mail\Services\MailTransportFactory');
        if (is_null($this->transport)) {
            throw new \RuntimeException(
                sprintf('Mail Transport Service is not found.')
            );
        }

        $this->message =   $services->get('Mail\Services\MailMessageFactory');
        if (is_null($this->message)) {
            throw new \RuntimeException(
                sprintf('Mail Message Service is not found.')
            );
        }

        $this->signature = $services->get('Mail\Services\MailSignatureService');
        if (is_null($this->signature)) {
            throw new \RuntimeException(
                sprintf('Mail Signature Service is not found.')
            );
        }

        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['User']['text_domain']) ?
            $config['User']['text_domain'] : null;


        $translator = $services->get('translator');

        $this->setTranslatorTextDomain($textDomain);
        $this->setTranslator($translator, $textDomain);

        return $this;
    }


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
                $mail = new Mail\CredentialsMail($this->message, $this->transport);
                break;

            case self::REGISTRATION_MAIL:
                $mail = new Mail\RegistrationMail($this->message, $this->transport);
                break;

            case self::VERIFY_MAIL:
                $mail = new Mail\VerifyMail($this->message, $this->transport);
                break;

            case self::COUPON_MAIL:
                $mail = new Mail\CouponMail($this->message, $this->transport);
                break;

            default:
                throw new \RuntimeException(
                    sprintf('An unknown mail type was provided.')
                );
        }

        $mail->setTranslator($this->getTranslator());
        $mail->setTranslatorTextDomain($this->getTranslatorTextDomain());
        $mail->setSignature($this->getSignature());
        return $mail;
    }


    /**
     * @return \Mail\Services\MailMessageFactory
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return \Mail\Services\MailSignatureService
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @return \Zend\Mail\Transport\TransportInterface
     */
    public function getTransport()
    {
        return $this->transport;
    }

}

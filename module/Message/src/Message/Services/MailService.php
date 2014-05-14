<?php

namespace Message\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nakade\Abstracts\AbstractTranslation;
use Message\Mail\NotifyMail;
/**
 * Class MailService
 *
 * @package Appointment\Services
 */
class MailService extends AbstractTranslation implements FactoryInterface
{
    const NOTIFY_MAIL = 'notify';

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

        //configuration
        $textDomain = isset($config['Message']['text_domain']) ?
            $config['Message']['text_domain'] : null;


        $translator = $services->get('translator');
        $this->setTranslator($translator, $textDomain);

        return $this;
    }


    /**
     * @param string $typ
     *
     * @return NotifyMail
     *
     * @throws \RuntimeException
     */
    public function getMail($typ)
    {

        switch (strtolower($typ)) {

            case self::NOTIFY_MAIL:
                $mail = new NotifyMail($this->message, $this->transport);
                break;

            default:
                throw new \RuntimeException(
                    sprintf('An unknown mail type was provided.')
                );
        }

        $mail->setTranslator($this->getTranslator());
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

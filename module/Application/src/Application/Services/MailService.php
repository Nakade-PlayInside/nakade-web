<?php

namespace Application\Services;

use Application\Mail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nakade\Abstracts\AbstractTranslation;

/**
 * Class MailService
 *
 * @package Application\Services
 */
class MailService extends AbstractTranslation implements FactoryInterface
{
    const CONTACT_MAIL = 'contact';

    private $transport;
    private $message;
    private $signature;
    private $bbc;

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
        $bbc = isset($config['Application']['contact']['bbc']) ?
            $config['Application']['contact']['bbc'] : array();

        //configuration
        $textDomain = isset($config['Application']['text_domain']) ?
            $config['Application']['text_domain'] : null;


        $translator = $services->get('translator');
        $this->setTranslatorTextDomain($textDomain);
        $this->setTranslator($translator);
        $this->setBbc($bbc);

        return $this;
    }


    /**
     * @param string $typ
     *
     * @return \Mail\NakadeMail
     *
     * @throws \RuntimeException
     */
    public function getMail($typ)
    {

        switch (strtolower($typ)) {

            case self::CONTACT_MAIL:
                $mail = new Mail\ContactMail($this->message, $this->transport);
                break;

            default:
                throw new \RuntimeException(
                    sprintf('An unknown mail type was provided.')
                );
        }

        if ($this->hasBbc()) {
            $bbc = $this->getBbc();
            $mail->setBbc($bbc);
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

    /**
     * @param array $bbc
     *
     * @return $this
     */
    public function setBbc(array $bbc)
    {
        $this->bbc = $bbc;
        return $this;
    }

    /**
     * @return array
     */
    public function getBbc()
    {
        return $this->bbc;
    }

    /**
     * @return bool
     */
    public function hasBbc()
    {
        return !empty($this->bbc);
    }
}

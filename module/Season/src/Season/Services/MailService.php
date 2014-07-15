<?php

namespace Season\Services;

use Season\Mail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nakade\Abstracts\AbstractTranslation;
/**
 * Class MailService
 *
 * @package Season\Services
 */
class MailService extends AbstractTranslation implements FactoryInterface
{

    const INVITATION_MAIL = 'invite';
    const SCHEDULE_MAIL = 'schedule';

    private $transport;
    private $message;
    private $signature;
    private $dateHelper;
    private $url='http://www.nakade.de';

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

        $this->dateHelper = $services->get('Season\Services\DateHelperService');

        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['Season']['text_domain']) ?
            $config['Season']['text_domain'] : null;

        //url
        if (isset($config['Season']['url'])) {
            $this->url =  $config['Season']['url'];
        }


        $translator = $services->get('translator');

        $this->setTranslatorTextDomain($textDomain);
        $this->setTranslator($translator, $textDomain);

        return $this;
    }


    /**
     * @param string $typ
     *
     * @return \Season\Mail\SeasonMail
     *
     * @throws \RuntimeException
     */
    public function getMail($typ)
    {
        switch (strtolower($typ)) {

           case self::INVITATION_MAIL:
               $mail = new Mail\InvitationMail($this->message, $this->transport, $this->dateHelper);
               break;

           case self::SCHEDULE_MAIL:
               $mail = new Mail\ScheduleMail($this->message, $this->transport, $this->dateHelper);
               break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown mail type was provided.')
               );
        }

        $mail->setTranslator($this->getTranslator());
        $mail->setTranslatorTextDomain($this->getTranslatorTextDomain());
        $mail->setSignature($this->getSignature());
        $mail->setUrl($this->getUrl());
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
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }


}

<?php

namespace Appointment\Services;

use Appointment\Mail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nakade\Abstracts\AbstractTranslation;
use \Mail\Services\MailMessageFactory;
use \Mail\Services\MailTransportFactory;
/**
 * Class MailService
 *
 * @package Appointment\Services
 */
class MailService extends AbstractTranslation implements FactoryInterface
{

    const SUBMITTER_MAIL = 'submitter';
    const RESPONDER_MAIL = 'responder';
    const CONFIRM_MAIL = 'confirm';
    const REJECT_MAIL = 'reject';

    private $transport;
    private $message;
    private $signature;
    private $confirmTime='72';
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

        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['Appointment']['text_domain']) ?
            $config['Appointment']['text_domain'] : null;

        //url
        if (isset($config['Appointment']['url'])) {
            $this->url =  $config['Appointment']['url'];
        }

        //time
        if (isset($config['Appointment']['auto_confirm_time'])) {
            $this->confirmTime =  strval($config['Appointment']['auto_confirm_time']);
        }

        $translator = $services->get('translator');

        //@todo: proof if this text domain setting is already doing the job
        $this->setTranslator($translator, $textDomain);

        return $this;
    }


    /**
     * @param string $typ
     *
     * @return \Appointment\Mail\AppointmentMail
     *
     * @throws \RuntimeException
     */
    public function getMail($typ)
    {
        switch (strtolower($typ)) {

           case self::CONFIRM_MAIL:
               $mail = new Mail\ConfirmMail($this->message, $this->transport);
               break;

           case self::SUBMITTER_MAIL:
               $mail = new Mail\SubmitterMail($this->message, $this->transport);
               break;

           case self::REJECT_MAIL:
               $mail = new Mail\RejectMail($this->message, $this->transport);
               break;

           case self::RESPONDER_MAIL:
               $mail = new Mail\ResponderMail($this->message, $this->transport);
               break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown mail type was provided.')
               );
        }

        $mail->setTranslator($this->getTranslator());
        $mail->setSignature($this->getSignature());
        $mail->setTime($this->getConfirmTime());
        $mail->setUrl($this->getUrl());
        return $mail;
    }

    /**
     * @return string
     */
    public function getConfirmTime()
    {
        return $this->confirmTime;
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

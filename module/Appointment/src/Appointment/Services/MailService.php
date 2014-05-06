<?php

namespace Appointment\Services;

use Appointment\Mail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nakade\Abstracts\AbstractTranslation;
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

        $config  = $services->get('config');

        //configuration
        $textDomain = isset($config['Appointment']['text_domain']) ?
            $config['Appointment']['text_domain'] : null;

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
        return $mail;
    }



}

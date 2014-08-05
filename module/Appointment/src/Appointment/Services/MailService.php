<?php

namespace Appointment\Services;

use Appointment\Mail;
use Mail\Services\AbstractMailService;
/**
 * Class MailService
 *
 * @package Appointment\Services
 */
class MailService extends AbstractMailService
{

    const SUBMITTER_MAIL = 'submitter';
    const RESPONDER_MAIL = 'responder';
    const CONFIRM_MAIL = 'confirm';
    const REJECT_MAIL = 'reject';

    private $confirmTime;

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
               $mail = new Mail\ConfirmMail($this->getMessage(), $this->getTransport());
               break;

           case self::SUBMITTER_MAIL:
               $mail = new Mail\SubmitterMail($this->getMessage(), $this->getTransport());
               break;

           case self::REJECT_MAIL:
               $mail = new Mail\RejectMail($this->getMessage(), $this->getTransport());
               break;

           case self::RESPONDER_MAIL:
               $mail = new Mail\ResponderMail($this->getMessage(), $this->getTransport());
               break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown mail type was provided.')
               );
        }


        $mail->setTranslator($this->getTranslator());
        $mail->setTranslatorTextDomain($this->getTextDomain());
        $mail->setSignature($this->getSignature());
        $mail->setTime($this->getConfirmTime());

        return $mail;
    }

    /**
     * @return mixed
     */
    public function getTextDomain()
    {
        if (is_null($this->textDomain)) {
            $config  = $this->getConfig();
            if (isset($config['Appointment']['text_domain'])) {
                $this->textDomain = $config['Appointment']['text_domain'];
            }
        }
        return $this->textDomain;
    }

    /**
     * @return string
     */
    public function getConfirmTime()
    {
        if (is_null($this->confirmTime)) {
            $config  = $this->getConfig();
            if (isset($config['Appointment']['auto_confirm_time'])) {
                $this->confirmTime = $config['Appointment']['auto_confirm_time'];
            }
        }
        return $this->confirmTime;
    }

}

<?php

namespace League\Services;

use League\Mail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nakade\Abstracts\AbstractTranslation;
use \Mail\Services\MailMessageFactory;
use \Mail\Services\MailTransportFactory;
/**
 * Class MailService
 *
 * @package League\Services
 */
class MailService extends AbstractTranslation implements FactoryInterface
{

    const RESULT_MAIL = 'result';
    const SCHEDULE_MAIL = 'schedule';
    const MATCH_REMINDER_MAIL = 'match_reminder';
    const RESULT_REMINDER_MAIL = 'result_reminder';
    const AUTO_RESULT_MAIL = 'auto_result';

    private $transport;
    private $message;
    private $signature;
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
        $textDomain = isset($config['League']['text_domain']) ?
            $config['League']['text_domain'] : null;

        //url
        if (isset($config['League']['url'])) {
            $this->url =  $config['League']['url'];
        }


        $translator = $services->get('translator');

        $this->setTranslatorTextDomain($textDomain);
        $this->setTranslator($translator, $textDomain);

        return $this;
    }


    /**
     * @param string $typ
     *
     * @return \League\Mail\LeagueMail
     *
     * @throws \RuntimeException
     */
    public function getMail($typ)
    {
        switch (strtolower($typ)) {

           case self::RESULT_MAIL:
               $mail = new Mail\ResultMail($this->message, $this->transport);
               break;

           case self::SCHEDULE_MAIL:
               $mail = new Mail\ScheduleMail($this->message, $this->transport);
               break;

           case self::MATCH_REMINDER_MAIL:
               $mail = new Mail\MatchReminderMail($this->message, $this->transport);
               break;

           case self::RESULT_REMINDER_MAIL:
               $mail = new Mail\ResultReminderMail($this->message, $this->transport);
               break;

           case self::AUTO_RESULT_MAIL:
               $mail = new Mail\AutoResultMail($this->message, $this->transport);
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

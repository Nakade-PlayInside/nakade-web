<?php

namespace Season\Services;

use Season\Mail;
use Mail\Services\AbstractMailService;
/**
 * Class MailService
 *
 * @package Season\Services
 */
class MailService extends AbstractMailService
{

    const INVITATION_MAIL = 'invite';
    const SCHEDULE_MAIL = 'schedule';

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
               $mail = new Mail\InvitationMail($this->getMessage(), $this->getTransport(), $this->getDateHelper());
               break;

           case self::SCHEDULE_MAIL:
               $mail = new Mail\ScheduleMail($this->getMessage(), $this->getTransport(), $this->getDateHelper());
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
            if (isset($config['Season']['text_domain'])) {
                $this->textDomain = $config['Season']['text_domain'];
            }
        }
        return $this->textDomain;
    }

    /**
     * @return mixed
     */
    public function getDateHelper()
    {
        return $this->getServices()->get('Season\Services\DateHelperService');
    }

}

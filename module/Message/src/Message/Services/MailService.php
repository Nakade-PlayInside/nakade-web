<?php

namespace Message\Services;

use Mail\Services\AbstractMailService;
use Message\Mail\NotifyMail;
/**
 * Class MailService
 *
 * @package Message\Services
 */
class MailService extends AbstractMailService
{
    const NOTIFY_MAIL = 'notify';

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
                $mail = new NotifyMail($this->getMessage(), $this->getTransport());
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
            if (isset($config['Message']['text_domain'])) {
                $this->textDomain = $config['Message']['text_domain'];
            }
        }
        return $this->textDomain;
    }

}

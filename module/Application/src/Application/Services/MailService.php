<?php

namespace Application\Services;

use Application\Mail;
use Mail\Services\AbstractMailService;

/**
 * Class MailService
 *
 * @package Application\Services
 */
class MailService extends AbstractMailService
{
    const CONTACT_MAIL = 'contact';
    private $bbc;

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
                $mail = new Mail\ContactMail($this->getMessage(), $this->getTransport());
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
            if (isset($config['Application']['text_domain'])) {
                $this->textDomain = $config['Application']['text_domain'];
            }
        }
        return $this->textDomain;
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
        if (is_null($this->bbc)) {
            $config  = $this->getConfig();
            if (isset($config['Application']['contact']['bbc'])) {
                $this->bbc = $config['Application']['contact']['bbc'];
            }
        }

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

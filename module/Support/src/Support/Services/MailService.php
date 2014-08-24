<?php

namespace Support\Services;

use Support\Mail;
use Mail\Services\AbstractMailService;

/**
 * Class MailService
 *
 * @package Support\Services
 */
class MailService extends AbstractMailService
{

    const REPLY_INFO_MAIL = 'reply_info';
    const STAGE_CHANGED_MAIL = 'stage';
    const TICKET_MAIL = 'ticket';
    const REFEREE_NOMINATION_MAIL = 'referee_nomination';
    const LM_NOMINATION_MAIL = 'lm_nomination';

    /**
     * @param string $typ
     *
     * @return \Support\Mail\SupportMail
     *
     * @throws \RuntimeException
     */
    public function getMail($typ)
    {
        switch (strtolower($typ)) {

            case self::REPLY_INFO_MAIL:
                $mail = new Mail\ReplyInfoMail($this->getMessage(), $this->getTransport());
                break;

            case self::STAGE_CHANGED_MAIL:
                $mail = new Mail\StageChangedMail($this->getMessage(), $this->getTransport());
                break;

            case self::TICKET_MAIL:
                $mail = new Mail\TicketMail($this->getMessage(), $this->getTransport());
                break;

            case self::REFEREE_NOMINATION_MAIL:
                $mail = new Mail\RefereeNominationMail($this->getMessage(), $this->getTransport());
                break;

            case self::LM_NOMINATION_MAIL:
                $mail = new Mail\LeagueManagerNominationMail($this->getMessage(), $this->getTransport());
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
            if (isset($config['Support']['text_domain'])) {
                $this->textDomain = $config['Support']['text_domain'];
            }
        }
        return $this->textDomain;
    }


}

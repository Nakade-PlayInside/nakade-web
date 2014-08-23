<?php

namespace League\Services;

use League\Mail;
use Mail\Services\AbstractMailService;

/**
 * Class MailService
 *
 * @package League\Services
 */
class MailService extends AbstractMailService
{

    const RESULT_MAIL = 'result';
    const SCHEDULE_MAIL = 'schedule';
    const MATCH_REMINDER_MAIL = 'match_reminder';
    const RESULT_REMINDER_MAIL = 'result_reminder';
    const AUTO_RESULT_MAIL = 'auto_result';
    const APPOINTMENT_REMINDER_MAIL = 'appointment_reminder';
    const EDITED_RESULT__MAIL = 'result_edited';

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
                $mail = new Mail\ResultMail($this->getMessage(), $this->getTransport());
                break;

            case self::SCHEDULE_MAIL:
                $mail = new Mail\ScheduleMail($this->getMessage(), $this->getTransport());
                break;

            case self::MATCH_REMINDER_MAIL:
                $mail = new Mail\MatchReminderMail($this->getMessage(), $this->getTransport());
                break;

            case self::RESULT_REMINDER_MAIL:
                $mail = new Mail\ResultReminderMail($this->getMessage(), $this->getTransport());
                break;

            case self::AUTO_RESULT_MAIL:
                $mail = new Mail\AutoResultMail($this->getMessage(), $this->getTransport());
                break;

            case self::APPOINTMENT_REMINDER_MAIL:
                $mail = new Mail\AppointmentReminderMail($this->getMessage(), $this->getTransport());
                break;

            case self::EDITED_RESULT__MAIL:
                $mail = new Mail\ResultEditMail($this->getMessage(), $this->getTransport());
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
            if (isset($config['League']['text_domain'])) {
                $this->textDomain = $config['League']['text_domain'];
            }
        }
        return $this->textDomain;
    }


}

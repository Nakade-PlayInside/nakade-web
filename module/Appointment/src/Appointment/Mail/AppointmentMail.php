<?php
namespace Appointment\Mail;

use Appointment\Entity\Appointment;
use Mail\NakadeMail;
use Mail\Services\MailMessageFactory;
use \Zend\Mail\Transport\TransportInterface;

/**
 * Class AppointmentMail
 *
 * @package Appointment\Mail
 */
abstract class AppointmentMail extends NakadeMail
{
    protected $time = '72';
    protected $appointment;

     /**
     * @param MailMessageFactory $mailService
     * @param TransportInterface $transport
     */
    public function __construct(MailMessageFactory $mailService, TransportInterface $transport)
    {
        $this->mailService = $mailService;
        $this->transport = $transport;
    }

    /**
     * @param Appointment $appointment
     *
     * @return $this
     */
    public function setAppointment(Appointment $appointment)
    {
        $this->appointment = $appointment;
        return $this;
    }

    /**
     * @return Appointment
     */
    public function getAppointment()
    {
        return $this->appointment;
    }

    /**
     * @param string $time
     *
     * @return $this;
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    protected function makeReplacements(&$message)
    {
        $message = str_replace('%URL%', $this->getUrl(), $message);

        if (!is_null($this->getAppointment())) {

            $link = sprintf('%s/appointmentConfirm?id=%d&confirm=%s',
                $this->getUrl(),
                $this->getAppointment()->getId(),
                $this->getAppointment()->getConfirmString()
            );

            $message = str_replace('%MATCH_INFO%', $this->getAppointment()->getMatch()->getMatchInfo(), $message);
            $message = str_replace('%NEW_DATE%', $this->getAppointment()->getNewDate()->format('d.m.y H:i'), $message);
            $message = str_replace('%OLD_DATE%', $this->getAppointment()->getOldDate()->format('d.m.y H:i'), $message);
            $message = str_replace('%CONFIRM_LINK%', $link, $message);
        }
        $message = str_replace('%TIME%', $this->getTime(), $message);

    }


}
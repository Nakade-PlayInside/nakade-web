<?php
/**
 * User: Holger Maerz
 * Date: 16.04.14
 * Time: 11:47
 */

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
    protected $url = 'http://www.nakade.de';
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

    protected function getUrl()
    {
        return $this->url;
    }

    protected function makeReplacements(&$message)
    {
        $message = str_replace('%URL%', $this->getUrl(), $message);

        if (!is_null($this->getAppointment())) {
            $message = str_replace('%MATCH_INFO%', $this->getAppointment()->getMatch()->getMatchInfo(), $message);
            $message = str_replace('%NEW_DATE%', $this->getAppointment()->getNewDate()->format('d.m.y H:i'), $message);
            $message = str_replace('%OLD_DATE%', $this->getAppointment()->getOldDate()->format('d.m.y H:i'), $message);
        }
        $message = str_replace('%TIME%', '72', $message);

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
}
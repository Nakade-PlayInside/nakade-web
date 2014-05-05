<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 05.05.14
 * Time: 16:46
 */

namespace Appointment\Command;


use Appointment\Entity\Appointment;
use Appointment\Mapper\AppointmentMapper;

class ConfirmCommand {

    /* @var $repository AppointmentMapper */
    private $repository;

    /**
     * @param AppointmentMapper $repository
     */
    public function __construct(AppointmentMapper $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return AppointmentMapper
     */
    public function getRepository()
    {
        return $this->repository;
    }

    public function doConfirm(Appointment $appointment)
    {
        $appointment->setIsConfirmed(true);
        $newDate = $appointment->getNewDate();
        $match = $appointment->getMatch();
        $match->setDate($newDate);

        $this->getRepository()->save($match);
        $this->getRepository()->save($appointment);

        //sendMail
    }

}
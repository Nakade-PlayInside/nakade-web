<?php

namespace Appointment\Services;

use Appointment\Entity\Appointment;
use User\Entity\User;
use Season\Entity\Match;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class AppointmentValidService implements FactoryInterface
{

    private $repository;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     */
    public function createService(ServiceLocatorInterface $services)
    {

        /* @var $repository \Appointment\Services\RepositoryService */
        $this->repository =  $services->get(
            'Appointment\Services\RepositoryService'
        );

        return $this;

    }

    /**
     * Proving valid match, i.e. not null, no result, user is either black or white and no appointment
     * had been made before.
     *
     * @param User  $user
     * @param Match $match
     *
     * @return bool
     */
    public function isValidMatch(User $user, Match $match=null)
    {
        if (is_null($match) || $match->hasResult() || $this->hasAppointment($match)) {
            return false;
        }

        if (!$this->isValidUser($user, $match)) {
            return false;
        }

        return true;
    }

    /**
     * Proving valid appointment, i.e. not null, no confirm or reject yet, no result and if user is the required
     * responder.
     *
     * @param User        $user
     * @param Appointment $appointment
     *
     * @return bool
     */
    public function isValidConfirm(User $user, Appointment $appointment=null)
    {
        //not confirmed or rejected, no result yet
        if (is_null($appointment) || $this->isProcessed($appointment) || $appointment->getMatch()->hasResult()) {
            return false;
        }

        //valid responder
        if ($appointment->getResponder()->getId() != $user->getId()) {
            return false;
        }

        return true;
    }

    /**
     * Proving valid appointment, i.e. not null, no confirm or reject yet, no result and if confirm string provided
     * by mail is correct.
     *
     * @param string      $confirmString
     * @param Appointment $appointment
     *
     * @return bool
     */
    public function isValidLink($confirmString, Appointment $appointment=null)
    {
        //not confirmed or rejected, no result yet
        if (is_null($appointment) || $this->isProcessed($appointment) || $appointment->getMatch()->hasResult()) {
            return false;
        }

        //not confirmed or rejected, no result yet
        if (!$this->isConfirmedByLink($appointment, $confirmString)) {
            return false;
        }
        return true;
    }

    /**
     * @param Appointment $appointment
     * @param string      $confirmString
     *
     * @return bool
     */
    private function isConfirmedByLink(Appointment $appointment, $confirmString)
    {
        if (0 == strcmp($appointment->getConfirmString(), $confirmString)) {
            return true;
        }
        return false;
    }

    /**
     * @param Appointment $appointment
     *
     * @return bool
     */
    private function isProcessed(Appointment $appointment)
    {

        if ($appointment->isRejected() || $appointment->isConfirmed()) {
            return true;
        }
        return false;
    }

    /**
     * @param User  $user
     * @param Match $match
     *
     * @return bool
     */
    private function isValidUser(User $user, Match $match)
    {
        if ($match->getBlack()->getId() == $user->getId()) {
            return true;
        }

        if ($match->getWhite()->getId() == $user->getId()) {
            return true;
        }

        return false;
    }

    /**
     * @param Match $match
     *
     * @return bool
     */
    private function hasAppointment(Match $match)
    {
        /* @var $mapper \Appointment\Mapper\AppointmentMapper */
        $mapper = $this->getRepository()->getMapper('appointment');
        $result = $mapper->getAppointmentByMatch($match);

        return !empty($result);
    }

    /**
     * @return \Appointment\Services\RepositoryService
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param RepositoryService $service
     *
     * @return $this
     */
    public function setRepository(RepositoryService $service)
    {
        $this->repository = $service;
        return $this;

    }

}

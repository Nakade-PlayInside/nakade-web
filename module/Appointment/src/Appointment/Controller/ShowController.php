<?php
/**
 * Controller Appointment
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

namespace Appointment\Controller;

use Appointment\Services\RepositoryService;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Class ShowController
 *
 * @package Appointment\Controller
 */
class ShowController extends AbstractController
{
    private $deadline;

    /**
     * @return array|ViewModel
     */
   public function indexAction()
   {

       /* @var $repo \Appointment\Mapper\AppointmentMapper */
       $repo = $this->getRepository()->getMapper(RepositoryService::APPOINTMENT_MAPPER);
       $appointments = $repo->getOpenConfirmsByUser($this->identity());

       return new ViewModel(
           array(
               'confirmMatches' => $appointments
           )
       );
   }

    /**
     * @return array|ViewModel
     */
    public function availableAction()
    {
        /* @var $repo \Appointment\Mapper\AppointmentMapper */
        $repo = $this->getRepository()->getMapper(RepositoryService::APPOINTMENT_MAPPER);
        $deadline = $this->getDeadline();

        /* @var $matchRepo \Appointment\Mapper\AppointmentMapper */
        $availableMatches = $repo->getMatchesOpenForAppointmentByUser(
            $this->identity()->getId(),
            $deadline
        );

        return new ViewModel(
            array(
                'availableMatches' => $availableMatches
            )
        );
    }

    /**
     * @return array|ViewModel
     */
    public function messageAction()
    {
        /* @var $repo \Appointment\Mapper\AppointmentMapper */
        $repo = $this->getRepository()->getMapper(RepositoryService::APPOINTMENT_MAPPER);
        $appointments = $repo->getOpenConfirmsByUser($this->identity());

        return new ViewModel(
            array(
                'noConfirm' => count($appointments)
            )
        );
    }

    /**
     * @return array|ViewModel
     */
    public function rejectAction()
    {
        /* @var $repo \Appointment\Mapper\AppointmentMapper */
        $repo = $this->getRepository()->getMapper(RepositoryService::APPOINTMENT_MAPPER);
        $appointments = $repo->getRejectedAppointments();

        return new ViewModel(
            array(
                'rejectedAppointments' => $appointments
            )
        );
    }

    /**
     * @param string $deadline
     *
     * @return $this
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

}
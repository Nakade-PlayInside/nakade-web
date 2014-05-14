<?php
/**
 * Controller Appointment
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

namespace Appointment\Controller;

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
       $repo = $this->getRepository()->getMapper('appointment');
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
        $repo = $this->getRepository()->getMapper('appointment');
        $shiftedMatches = $repo->getMatchIdsByUser($this->identity());
        $deadline = $this->getDeadline();

        /* @var $matchRepo \League\Mapper\MatchMapper */
        $matchRepo = $this->getRepository()->getMapper('match');
        $availableMatches = $matchRepo->getMatchesOpenForAppointmentByUser(
            $this->identity(),
            $shiftedMatches,
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
        $repo = $this->getRepository()->getMapper('appointment');
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
        $repo = $this->getRepository()->getMapper('appointment');
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
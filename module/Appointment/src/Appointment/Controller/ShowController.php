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
        /*
        if ($this->identity()->getRole() != 'admin') {
           @todo: action for moderator or admin
        }*/


        /* @var $repo \Appointment\Mapper\AppointmentMapper */
        $repo = $this->getRepository()->getMapper('appointment');
        $appointments = $repo->getRejectedAppointments();

        return new ViewModel(
            array(
                'rejectedAppointments' => $appointments
            )
        );
    }

}
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

}
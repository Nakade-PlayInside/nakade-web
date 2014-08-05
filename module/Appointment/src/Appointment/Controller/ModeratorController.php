<?php
namespace Appointment\Controller;

use Appointment\Form\AppointmentInterface;
use Appointment\Pagination\AppointmentPagination;
use Appointment\Services\RepositoryService;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Class ModeratorController
 *
 * @package Appointment\Controller
 */
class ModeratorController extends AbstractController implements AppointmentInterface
{
    //todo: make new matchDate
    /**
     * @return array|ViewModel
     */
   public function indexAction()
   {
       $page = (int) $this->params()->fromRoute('id', 1);

       /* @var $entityManager \Doctrine\ORM\EntityManager */
       $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
       $myPagination = new AppointmentPagination($entityManager);
       $offset = (AppointmentPagination::ITEMS_PER_PAGE * ($page -1));//value for mapper request

       return new ViewModel(
           array(
               'appointments' => $this->getMapper()->getAppointmentsByPages($offset),
               'paginator' =>   $myPagination->getPagination($page),
           )
       );
   }

    /**
     * @return array|ViewModel
     */
    public function infoAction()
    {
        $id = (int) $this->params()->fromRoute('id', -1);
        $appointment = $this->getMapper()->getAppointmentById($id);

        return new ViewModel(
            array(
                'appointment' => $appointment
            )
        );
    }


    /**
     * @return \Appointment\Mapper\AppointmentMapper
     */
    public function getMapper()
   {
       return $this->getRepository()->getMapper(RepositoryService::APPOINTMENT_MAPPER);
   }

}
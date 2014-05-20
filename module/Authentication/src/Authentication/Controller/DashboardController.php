<?php
//module/SanAuth/src/SanAuth/Controller/SuccessController.php
namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Message\Mapper\MessageMapper;

/**
 * Success controller for successful authentication of registered users.
 */
class DashboardController extends AbstractActionController
{
    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
       $noNewMails = 0;
       if ($this->identity()) {
           $serviceManager = $this->getServiceLocator();
           $entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');
           $repo = new MessageMapper();
           $repo->setEntityManager($entityManager);
           $user = $this->identity()->getId();
           $noNewMails = $repo->getNumberOfNewMessages($user);
       }

        $scheduleWidget  = $this->forward()
            ->dispatch('/League/Controller/ActualSeason', array('action' => 'mySchedule'));

        $appointmentWidget  = $this->forward()
            ->dispatch('/Appointment/Controller/Show', array('action' => 'message'));

        $page = new ViewModel(array('noNewMails' => $noNewMails));
        $page->addChild($appointmentWidget, 'appointmentWidget');
        $page->addChild($scheduleWidget, 'scheduleWidget');

        return $page;
    }



}
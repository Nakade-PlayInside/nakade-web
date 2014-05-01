<?php
/**
 * Controller Appointment
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

// module/Appointment/src/Appointment/Controller/AppointmentController.php:
namespace Appointment\Controller;

use Appointment\Form\AppointmentForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AppointmentController extends AbstractActionController
{

    /**
     * @return array|ViewModel
     */
   public function indexAction()
   {
       $user = $this->identity();
       #match id=6

       $sm = $this->getServiceLocator();
       $em = $sm->get('Doctrine\ORM\EntityManager');

       $repo = new \League\Mapper\MatchMapper();
       $repo->setEntityManager($em);

       /* @var $match \League\Entity\Match */
       $match = $repo->getMatchById(6);

       $matchInfo = sprintf("%s: %s - %s",
           $match->getDate()->format('d.M  H:i'),
           $match->getBlack()->getShortName(),
           $match->getWhite()->getShortName()
       );

       $form = new AppointmentForm();

       if ($this->getRequest()->isPost()) {

           //get post data, set data to from, prepare for validation
           $postData =  $this->getRequest()->getPost();

           //cancel
           if ($postData['cancel']) {
               return $this->redirect()->toRoute('message');
           }

           $form->setData($postData);

           if ($form->isValid()) {

               var_dump("what");//die;
               //return $this->redirect()->toRoute('message');
           }
       }


       return new ViewModel(
           array(
               'appointment' => null,
               'form' => $form,
               'matchInfo' => $matchInfo
           )
       );
   }


}
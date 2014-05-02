<?php
/**
 * Controller Appointment
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

// module/Appointment/src/Appointment/Controller/AppointmentController.php:
namespace Appointment\Controller;

use Appointment\Entity\Appointment;
use Appointment\Form\AppointmentForm;
use Zend\Form\Element\DateTime;
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

       $endDate = new \DateTime('now');
       $endDate->modify('+4 months');
       $form = new AppointmentForm($endDate);

       if ($this->getRequest()->isPost()) {

           //get post data, set data to from, prepare for validation
           $postData =  $this->getRequest()->getPost();

           //cancel
           if ($postData['cancel']) {
               return $this->redirect()->toRoute('message');
           }

           $form->setData($postData);

           if ($form->isValid()) {

               $appointment = new Appointment();

               $data = $form->getData();
               $newDate = \DateTime::createFromFormat('Y-m-d H:i:s', $data['date'] . ' ' . $data['time']);

               $appointment->setMatch($match);
               $appointment->setSubmitter($match->getBlack());
               $appointment->setResponder($match->getWhite());
               $appointment->setSubmitDate(new \DateTime());
               $appointment->setOldDate($match->getDate());
               $appointment->setNewDate($newDate);

               $em->persist($appointment);
               $em->flush($appointment);
              //make email
               //send email

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
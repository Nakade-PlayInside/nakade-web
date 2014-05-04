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
use Appointment\Form\ConfirmForm;
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

    /**
     * @return array|ViewModel
     */
    public function confirmAction()
    {
        $sm = $this->getServiceLocator();
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $sm->get('Doctrine\ORM\EntityManager');

        $repo = new \Appointment\Mapper\AppointmentMapper();
        $repo->setEntityManager($em);


        $appointment = $repo->getAppointmentById(1);
        $matchInfo = sprintf("%s - %s",
            $appointment->getMatch()->getBlack()->getShortName(),
            $appointment->getMatch()->getWhite()->getShortName()
        );

        if ($appointment->isConfirmed() || $appointment->isRejected()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new ConfirmForm();

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['reject']) {

                var_dump("NO");
                //return $this->redirect()->toRoute('message');
            }

            $form->setData($postData);

            if ($form->isValid() && $postData['confirm']) {

                var_dump("YES");


                $match = $appointment->getMatch();
                $date = $appointment->getNewDate();
                $appointment->setIsConfirmed(true);
                $appointment->setIsDone(true);

                $match->setDate($date);

                $em->persist($appointment);
                $em->flush($appointment);
                $em->persist($match);
                $em->flush($match);

                //make email
                //send email

                return $this->redirect()->toRoute('appointment', array(
                    'action' => 'success'
                ));
            }
        }


        return new ViewModel(
            array(
                'appointment' => $appointment,
                'oldDate' => $appointment->getOldDate()->format('d.m.Y'),
                'newDate' => $appointment->getNewDate()->format('d.m.Y'),
                'form' => $form,
                'matchInfo' => $matchInfo
            )
        );
    }

    /**
     * @return array|ViewModel
     */
    public function rejectAction()
    {
        $sm = $this->getServiceLocator();
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $sm->get('Doctrine\ORM\EntityManager');

        $repo = new \Appointment\Mapper\AppointmentMapper();
        $repo->setEntityManager($em);


        $appointment = $repo->getAppointmentById(1);
        $matchInfo = sprintf("%s - %s",
            $appointment->getMatch()->getBlack()->getShortName(),
            $appointment->getMatch()->getWhite()->getShortName()
        );

        if ($appointment->isConfirmed() || $appointment->isRejected()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new ConfirmForm();

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['reject']) {

                var_dump("NO");
                //return $this->redirect()->toRoute('message');
            }

            $form->setData($postData);

            if ($form->isValid() && $postData['confirm']) {

                var_dump("YES");


                $match = $appointment->getMatch();
                $date = $appointment->getNewDate();
                $appointment->setIsConfirmed(true);
                $appointment->setIsDone(true);

                $match->setDate($date);

                $em->persist($appointment);
                $em->flush($appointment);
                $em->persist($match);
                $em->flush($match);

                //make email
                //send email

                return $this->redirect()->toRoute('appointment', array(
                    'action' => 'success'
                ));
            }
        }


        return new ViewModel(
            array(
                'appointment' => $appointment,
                'oldDate' => $appointment->getOldDate()->format('d.m.Y'),
                'newDate' => $appointment->getNewDate()->format('d.m.Y'),
                'form' => $form,
                'matchInfo' => $matchInfo
            )
        );
    }

    /**
     * @return array|ViewModel
     */
    public function successAction()
    {
        return new ViewModel(
            array()
        );
    }
}
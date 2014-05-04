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
use Appointment\Form\RejectForm;
use Zend\Form\Element\DateTime;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

//
// 2. email
// 2b factories for controller, form and email
// 3. config
// 4. confirm by email
// 5. automatic confirm after time exceed
// 6. user right

class AppointmentController extends AbstractActionController
{

    /**
     * @return array|ViewModel
     */
   public function indexAction()
   {
       $user = $this->identity();

       //provide MATCHId
       $matchId  = (int) $this->params()->fromRoute('id', -1);
       //proof matchId
       //proof on already existing appoinment
       //proof if id is matching

       $sm = $this->getServiceLocator();
       $em = $sm->get('Doctrine\ORM\EntityManager');

       $repo = new \League\Mapper\MatchMapper();
       $repo->setEntityManager($em);

       /* @var $match \League\Entity\Match */
       $match = $repo->getMatchById($matchId);

       //get league from match; get season from league; get last date from season
       $endDate = $match->getDate();
       $endDate->modify('+1 months');

       $form = new AppointmentForm($endDate);

       /* @var $request \Zend\Http\Request */
       $request = $this->getRequest();
       if ($request->isPost()) {

           $postData =  $request->getPost();

           //cancel
           if ($postData['cancel']) {
               //todo: change route finally to origin
               return $this->redirect()->toRoute('home');
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
               'form' => $form,
               'match' => $match
           )
       );
   }

    /**
     * @return array|ViewModel
     */
    public function confirmAction()
    {

        // todo: confirmation deadline if excceding time period for confirming: automatic confirmation!

        //provide appointmentId
        $appointmentId  = (int) $this->params()->fromRoute('id', -1);

        //proof on matching user

        $sm = $this->getServiceLocator();
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $sm->get('Doctrine\ORM\EntityManager');

        $repo = new \Appointment\Mapper\AppointmentMapper();
        $repo->setEntityManager($em);

        $appointment = $repo->getAppointmentById($appointmentId);


        if (is_null($appointment) || $appointment->isConfirmed() || $appointment->isRejected()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new ConfirmForm();

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //reject
            if ($postData['reject']) {
                return $this->redirect()->toRoute('appointment', array(
                        'action' => 'reject'
                ));
            }

            $form->setData($postData);

            if ($form->isValid() && $postData['confirm']) {

                $match = $appointment->getMatch();
                $date = $appointment->getNewDate();

                $appointment->setIsConfirmed(true);
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
                'oldDate' => $appointment->getOldDate()->format('d.m.Y H:i'),
                'newDate' => $appointment->getNewDate()->format('d.m.Y H:i'),
                'form' => $form,
                'matchInfo' => $appointment->getMatch()->getMatchInfo()
            )
        );
    }

    /**
     * @return array|ViewModel
     */
    public function rejectAction()
    {

        //provide appointmentId
        $appointmentId  = (int) $this->params()->fromRoute('id', -1);

        //proof on matching user

        $sm = $this->getServiceLocator();
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $sm->get('Doctrine\ORM\EntityManager');

        $repo = new \Appointment\Mapper\AppointmentMapper();
        $repo->setEntityManager($em);


        $appointment = $repo->getAppointmentById($appointmentId);
        if (is_null($appointment) || $appointment->isConfirmed() || $appointment->isRejected()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new RejectForm();

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['cancel']) {

                return $this->redirect()->toRoute('appointment', array(
                    'action' => 'confirm'
                ));
            }

            $form->setData($postData);

            if ($form->isValid() && $postData['reject']) {

                $data = $form->getData();
                $appointment->setIsRejected(true);
                $appointment->setRejectReason($data['reason']);

                $em->persist($appointment);
                $em->flush($appointment);

                //make email
                //send email to both players and league managers

                return $this->redirect()->toRoute('appointment', array(
                    'action' => 'info'
                ));
            }
        }


        return new ViewModel(
            array(
                'oldDate' => $appointment->getOldDate()->format('d.m.Y H:i'),
                'newDate' => $appointment->getNewDate()->format('d.m.Y H:i'),
                'form' => $form,
                'matchInfo' => $appointment->getMatch()->getMatchInfo()
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

    /**
     * @return array|ViewModel
     */
    public function infoAction()
    {
        return new ViewModel(
            array()
        );
    }

    private function isValidAppointment(Appointment $appointment)
    {
        /* @var $user /User/League/User */
        $user = $this->identity();


        return (is_null($appointment) || $appointment->isConfirmed() || $appointment->isRejected());
    }
}
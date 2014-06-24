<?php
/**
 * Controller Appointment
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

namespace Appointment\Controller;

use Appointment\Entity\Appointment;
use Appointment\Services\RepositoryService;
use Season\Entity\Match;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Class AppointmentController
 *
 * @package Appointment\Controller
 */
class AppointmentController extends AbstractController
{

    /**
     * @return array|ViewModel
     */
   public function indexAction()
   {
       $matchId  = (int) $this->params()->fromRoute('id', -1);

       /* @var $repo \Appointment\Mapper\AppointmentMapper */
       $repo = $this->getRepository()->getMapper(RepositoryService::APPOINTMENT_MAPPER);

       /* @var $match \Season\Entity\Match */
       $match = $repo->getMatchById($matchId);

       if (!$this->getService()->isValidMatch($this->identity(), $match)) {
           return $this->redirect()->toRoute('appointment', array(
               'action' => 'invalid'
           ));
       }

       $appointment = new Appointment();
       $appointment->setMatch($match);

       /* @var $form \Appointment\Form\AppointmentForm */
       $form = $this->getFormFactory()->getForm('appointment');
       $form->bindEntity($appointment);

       /* @var $request \Zend\Http\Request */
       $request = $this->getRequest();
       if ($request->isPost()) {

           $postData =  $request->getPost();

           //cancel
           if (isset($postData['button']['cancel'])) {
               return $this->redirect()->toRoute('appointmentShow', array('action' => 'available'));
           }

           $form->setData($postData);

           if ($form->isValid()) {

               /* @var $appointment \Appointment\Entity\Appointment */
               $appointment = $form->getData();
              // var_dump($appointment->getMatch()->getId());die;
               $repo->save($appointment);

               /* @var $submit \Appointment\Mail\SubmitterMail */
               $submit = $this->getMailService()->getMail('submitter');
               $submit->setAppointment($appointment);
               $submit->sendMail($appointment->getSubmitter());

               /* @var $responder \Appointment\Mail\ResponderMail */
               $responder = $this->getMailService()->getMail('responder');
               $responder->setAppointment($appointment);
               $responder->sendMail($appointment->getResponder());

               return $this->redirect()->toRoute('appointment', array(
                   'action' => 'submitted'
               ));
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

        //provide appointmentId
        $appointmentId  = (int) $this->params()->fromRoute('id', -1);

        /* @var $repo \Appointment\Mapper\AppointmentMapper */
        $repo = $this->getRepository()->getMapper(RepositoryService::APPOINTMENT_MAPPER);
        $appointment = $repo->getAppointmentById($appointmentId);


        if (!$this->getService()->isValidConfirm($this->identity(), $appointment)) {
            return $this->redirect()->toRoute('appointment', array(
                'action' => 'invalid'
            ));
        }

        /* @var $form \Appointment\Form\ConfirmForm */
        $form = $this->getFormFactory()->getForm('confirm');
        $form->bindEntity($appointment);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //reject
            if ($postData['reject']) {
                return $this->redirect()->toRoute('appointment', array(
                        'action' => 'reject',
                        'id' => $appointmentId,
                ));
            }

            $form->setData($postData);

            if ($form->isValid() && $postData['confirm']) {

                /* @var $appointment \Appointment\Entity\Appointment */
                $appointment = $form->getData();

                $match = $appointment->getMatch();
                $date = $appointment->getNewDate();
                $sequence = $match->getSequence() + 1;

                $appointment->setIsConfirmed(true);
                $match->setDate($date);
                $match->setSequence($sequence);

                $repo->save($appointment);
                $repo->save($match);

                /* @var $mail \Appointment\Mail\ConfirmMail */
                $mail = $this->getMailService()->getMail('confirm');
                $mail->setAppointment($appointment);
                $mail->sendMail($appointment->getResponder());
                $mail->sendMail($appointment->getSubmitter());


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

        /* @var $repo \Appointment\Mapper\AppointmentMapper */
        $repo = $this->getRepository()->getMapper(RepositoryService::APPOINTMENT_MAPPER);
        $appointment = $repo->getAppointmentById($appointmentId);

        if (!$this->getService()->isValidConfirm($this->identity(), $appointment)) {
            return $this->redirect()->toRoute('appointment', array(
                'action' => 'invalid'
            ));
        }

        /* @var $form \Appointment\Form\RejectForm */
        $form = $this->getFormFactory()->getForm('reject');
        $form->bindEntity($appointment);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('appointment', array(
                    'action' => 'confirm',
                    'id' => $appointmentId
                ));
            }

            $form->setData($postData);

            if ($form->isValid() && $postData['reject']) {

                /* @var $appointment \Appointment\Entity\Appointment */
                $appointment = $form->getData();
                $appointment->setIsRejected(true);
                $repo->save($appointment);

                /* @var $mail \Appointment\Mail\RejectMail */
                $mail = $this->getMailService()->getMail('reject');
                $mail->setAppointment($appointment);
                $mail->sendMail($appointment->getResponder());
                $mail->sendMail($appointment->getSubmitter());

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
    public function submittedAction()
    {
        return new ViewModel();
    }

    /**
     * @return array|ViewModel
     */
    public function successAction()
    {
        return new ViewModel();
    }

    /**
     * @return array|ViewModel
     */
    public function infoAction()
    {
        return new ViewModel();
    }

    /**
     * @return array|ViewModel
     */
    public function invalidAction()
    {
        return new ViewModel();
    }



}
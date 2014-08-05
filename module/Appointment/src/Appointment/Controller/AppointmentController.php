<?php
namespace Appointment\Controller;

use Appointment\Entity\Appointment;
use Appointment\Form\AppointmentInterface;
use Appointment\Services\AppointmentFormFactory;
use Appointment\Services\MailService;
use Appointment\Services\RepositoryService;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Class AppointmentController
 *
 * @package Appointment\Controller
 */
class AppointmentController extends AbstractController implements AppointmentInterface
{
//todo: cleanup method for played matches
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
       $form = $this->getFormFactory()->getForm(AppointmentFormFactory::APPOINTMENT_FORM);
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
               $repo->save($appointment);

               /* @var $submit \Appointment\Mail\SubmitterMail */
               $submit = $this->getMailService()->getMail(MailService::SUBMITTER_MAIL);
               $submit->setAppointment($appointment);
               $submit->sendMail($appointment->getSubmitter());

               /* @var $responder \Appointment\Mail\ResponderMail */
               $responder = $this->getMailService()->getMail(MailService::RESPONDER_MAIL);
               $responder->setAppointment($appointment);
               $responder->sendMail($appointment->getResponder());

               $this->flashMessenger()->addSuccessMessage('Appointment Made');
               return $this->redirect()->toRoute('appointment', array(
                   'action' => 'submitted'
               ));
           } else {
               $this->flashMessenger()->addErrorMessage('Input Error');
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
        $form = $this->getFormFactory()->getForm(AppointmentFormFactory::CONFIRM_FORM);
        $form->bindEntity($appointment);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData =  $request->getPost();

            //reject
            if (isset($postData[self::FIELD_REJECT_APPOINTMENT])) {
                return $this->redirect()->toRoute('appointment', array(
                        'action' => 'reject',
                        'id' => $appointmentId,
                ));
            }

            $form->setData($postData);
            if ($form->isValid()) {

                /* @var $appointment \Appointment\Entity\Appointment */
                $appointment = $form->getData();
                $repo->save($appointment);

                /* @var $mail \Appointment\Mail\ConfirmMail */
                $mail = $this->getMailService()->getMail(MailService::CONFIRM_MAIL);
                $mail->setAppointment($appointment);
                $mail->sendMail($appointment->getResponder());
                $mail->sendMail($appointment->getSubmitter());

                $this->flashMessenger()->addSuccessMessage('Appointment Confirmed');
                return $this->redirect()->toRoute('appointment', array(
                    'action' => 'success'
                ));
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }
        }


        return new ViewModel(
            array(
                'form' => $form,
                'appointment' => $appointment
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
        $form = $this->getFormFactory()->getForm(AppointmentFormFactory::REJECT_FORM);
        $form->bindEntity($appointment);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('appointment', array(
                    'action' => 'confirm',
                    'id' => $appointmentId
                ));
            }

            $form->setData($postData);

            if ($form->isValid()) {

                /* @var $appointment \Appointment\Entity\Appointment */
                $appointment = $form->getData();
                $repo->save($appointment);

                /* @var $mail \Appointment\Mail\RejectMail */
                $mail = $this->getMailService()->getMail(MailService::REJECT_MAIL);
                $mail->setAppointment($appointment);
                $mail->sendMail($appointment->getResponder());
                $mail->sendMail($appointment->getSubmitter());

                $this->flashMessenger()->addSuccessMessage('Appointment Rejected');
                return $this->redirect()->toRoute('appointment', array('action' => 'info'));
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }
        }


        return new ViewModel(
            array(
                'form' => $form,
                'appointment' => $appointment
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
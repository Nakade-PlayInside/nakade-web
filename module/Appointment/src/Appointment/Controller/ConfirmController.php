<?php
namespace Appointment\Controller;

use Appointment\Services\RepositoryService;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Confirm of an appointment by using link provided by email
 *
 * @package Appointment\Controller
 */
class ConfirmController extends AbstractController
{
    /**
     * Verification action. A direct link to this action is provided
     * in the user's verification mail.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {

       $appointmentId   = $this->params()->fromQuery('id', null);
       $confirmString   = $this->params()->fromQuery('confirm', null);

       //no params -> error
       if (empty($appointmentId) || empty($confirmString)) {
           return $this->redirect()->toRoute('appointmentConfirm', array('action' => 'error'));
       }

        /* @var $repo \Appointment\Mapper\AppointmentMapper */
       $repo = $this->getRepository()->getMapper(RepositoryService::APPOINTMENT_MAPPER);
       $appointment = $repo->getAppointmentById($appointmentId);

       if (!$this->getService()->isValidLink($confirmString, $appointment)) {
           return $this->redirect()->toRoute('appointmentConfirm', array('action' => 'failure'));
       }

       $appointment->setIsConfirmed(true);
       $match = $appointment->getMatch();
       $date = $appointment->getNewDate();
       $match->setDate($date);
       $sequence = $match->getSequence() + 1;
       $match->setSequence($sequence);


       $repo->save($match);
       $repo->save($appointment);

       /* @var $mail \Appointment\Mail\ConfirmMail */
       $mail = $this->getMailService()->getMail('confirm');
       $mail->setAppointment($appointment);
       $mail->sendMail($appointment->getResponder());
       $mail->sendMail($appointment->getSubmitter());

       return $this->redirect()->toRoute('appointmentConfirm', array('action' => 'success'));

    }

    /**
     * Activation failed.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function successAction()
    {
        return new ViewModel();
    }

    /**
     * Activation failed.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function failureAction()
    {
        return new ViewModel();
    }

    /**
     * Data missing.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function errorAction()
    {
        return new ViewModel();
    }

}

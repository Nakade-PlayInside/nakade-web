<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for
 * the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc.
 * (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Appointment\Controller;

use Appointment\Mapper\AppointmentMapper;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;
use Appointment\Services\MailService;

/**
 * Verify the account with new credentials.
 * Use the link of the activation mail send to the user.
 */
class ConfirmController extends AbstractController
{
    private $mailService;

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
       if (!isset($appointmentId) || !isset($confirmString)) {
           return $this->redirect()->toRoute('appointmentConfirm', array('action' => 'error'));
       }

        /* @var $repo \Appointment\Mapper\AppointmentMapper */
       $repo = $this->getRepository()->getMapper('appointment');
       $appointment = $repo->getAppointmentById($appointmentId);

       if (0 != strcmp($appointment->getConfirmString(), $confirmString)) {
           return $this->redirect()->toRoute('appointmentConfirm', array('action' => 'failure'));
       }

       if (is_null($appointment) || $appointment->isConfirmed() || $appointment->isRejected()) {
           return $this->redirect()->toRoute('appointmentConfirm', array('action' => 'failure'));
       }

       $appointment->setIsConfirmed(true);
       $match = $appointment->getMatch();
       $date = $appointment->getNewDate();
       $match->setDate($date);

       $repo->save($match);
       $repo->save($appointment);

       //send email to both players
       $mail = $this->getMailService()->getMail('confirm');
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

    /**
     * @param MailService $mail
     *
     * @return $this
     */
    public function setMailService(MailService $mail)
    {
        $this->mailService = $mail;
        return $this;
    }

    /**
     * @return MailService
     */
    public function getMailService()
    {
        return $this->mailService;
    }
}

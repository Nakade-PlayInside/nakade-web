<?php
namespace Appointment\Controller;

use Appointment\Form\AppointmentInterface;
use Appointment\Pagination\AppointmentPagination;
use Appointment\Services\AppointmentFormFactory;
use Appointment\Services\MailService;
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
     * @return array|ViewModel
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', -1);
        $appointment = $this->getMapper()->getAppointmentById($id);

        /* @var $form \Appointment\Form\MatchDateForm */
        $form = $this->getFormFactory()->getForm(AppointmentFormFactory::MATCH_DATE_FORM);
        $form->bindEntity($appointment);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $postData =  $request->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('appointmentModerator');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                /* @var $appointment \Appointment\Entity\Appointment */
                $appointment = $form->getData();
                $this->getMapper()->save($appointment);

                /* @var $submit \Appointment\Mail\SubmitterMail */
                $mail = $this->getMailService()->getMail(MailService::INFO_MAIL);
                $mail->setAppointment($appointment);
                $mail->sendMail($appointment->getMatch()->getBlack());
                $mail->sendMail($appointment->getMatch()->getWhite());

                $this->flashMessenger()->addSuccessMessage('Appointment Made');
                return $this->redirect()->toRoute('appointmentModerator');
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }
        }

        return new ViewModel(
            array(
                'form' => $form,
                'match' => $appointment->getMatch()
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
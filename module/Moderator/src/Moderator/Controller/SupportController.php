<?php
namespace Moderator\Controller;

use Moderator\Entity\LeagueManager;
use Moderator\Entity\SupportRequest;
use Moderator\Pagination\ModeratorPagination;
use Moderator\Services\FormService;
use Moderator\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * Class ManagerController
 *
 * @package Moderator\Controller
 */
class SupportController extends AbstractController
{
    /**
     *
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $page = (int) $this->params()->fromRoute('id', 1);

        /* @var $entityManager \Doctrine\ORM\EntityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $pagination = new ModeratorPagination($entityManager);
        $offset = (ModeratorPagination::ITEMS_PER_PAGE * ($page -1));

        return new ViewModel(
            array(
                'paginator' => $pagination->getPagination($page),
                'managers' =>  $this->getMapper()->getLeagueManagerByPages($offset),
            )
        );
    }

    /**
     *
     * @return ViewModel
     */
    public function overviewAction()
    {
        return new ViewModel(
            array(
            )
        );
    }

    /**
     *
     * @return ViewModel
     */
    public function myInquiriesAction()
    {
        $userId = $this->identity()->getId();

        $test = $this->getMapper()->getSupportRequests();
        $first = $test[0];
        $allMsg = $first->getMessages();
        return new ViewModel(
            array(
                'supportRequests' => $this->getMapper()->getSupportRequests(),
            )
        );
    }

    /**
     *
     * @return ViewModel
     */
    public function addAction()
    {
        /* @var $form \Moderator\Form\SupportForm */
        $form = $this->getForm(FormService::SUPPORT_FORM);
        $support = new SupportRequest();
        $form->bindEntity($support);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('manager');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $support = $form->getData();
//todo: mail for LM
                /* @var $mail \User\Mail\RegistrationMail */
                //    $mail = $this->getMailService()->getMail(MailService::REGISTRATION_MAIL);
                //    $mail->setUser($user);
                //    $mail->sendMail($user);

                $this->getMapper()->save($support);
                $this->flashMessenger()->addSuccessMessage('New Support Request');

                return $this->redirect()->toRoute('manager');
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }

        }

        return new ViewModel(
            array(
                'form'    => $form
            )
        );
    }

    /**
     *
     * @return ViewModel
     */
    public function deleteAction()
    {
        return new ViewModel(
            array(
            )
        );
    }

    /**
     *
     * @return ViewModel
     */
    public function cancelAction()
    {
        return new ViewModel(
            array(
            )
        );
    }

    /**
     *
     * @return ViewModel
     */
    public function acceptAction()
    {
        return new ViewModel(
            array(
            )
        );
    }

    /**
     *
     * @return ViewModel
     */
    public function assignAction()
    {
        return new ViewModel(
            array(
            )
        );
    }

    /**
     *
     * @return ViewModel
     */
    public function doneAction()
    {
        return new ViewModel(
            array(
            )
        );
    }


    /**
     * @return \Moderator\Mapper\ManagerMapper
     */
    private function getMapper()
    {
        /* @var $repo \Moderator\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::MANAGER_MAPPER);
    }

}

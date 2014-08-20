<?php
namespace Moderator\Controller;

use Moderator\Entity\LeagueManager;
use Moderator\Pagination\ModeratorPagination;
use Moderator\Services\FormService;
use Moderator\Services\MailService;
use Moderator\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * Class ManagerController
 *
 * @package Moderator\Controller
 */
class ManagerController extends AbstractController
{
    //todo: association owner can do this too
    //todo: association owner can do this to his owned association only
    //todo: what association depends on role

    /**
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
     * @return array|ViewModel
     */
    public function addAction()
    {
        /* @var $form \Moderator\Form\LeagueManagerForm */
        $form = $this->getForm(FormService::MANAGER_FORM);
        $manager = new LeagueManager();
        $form->bindEntity($manager);

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

                $manager = $form->getData();

                /* @var $mail \Moderator\Mail\LeagueManagerNominationMail */
                $mail = $this->getMailService()->getMail(MailService::LM_NOMINATION_MAIL);
                $mail->setLeagueManager($manager);
                $mail->sendMail($manager->getManager());

                $this->getMapper()->save($manager);
                $this->flashMessenger()->addSuccessMessage('New League Manager added');

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
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        //get param
        $uid  = $this->params()->fromRoute('id', null);

        /* @var $leagueManager \Moderator\Entity\LeagueManager */
        $leagueManager = $this->getMapper()->getLeagueManagerById($uid);
        if (!is_null($leagueManager)) {
            $leagueManager->setIsActive(false);
            $this->getMapper()->save($leagueManager);
            $this->flashMessenger()->addSuccessMessage('League Manager deactivated');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute('manager');
    }

    /**
     * @return \Zend\Http\Response
     */
    public function unDeleteAction()
    {
        //get param
        $uid  = $this->params()->fromRoute('id', null);


        /* @var $leagueManager \Moderator\Entity\LeagueManager */
        $leagueManager = $this->getMapper()->getLeagueManagerById($uid);
        if (!is_null($leagueManager)) {
            $leagueManager->setIsActive(true);
            $this->getMapper()->save($leagueManager);
            $this->flashMessenger()->addSuccessMessage('League Manager activated');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute('manager');
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

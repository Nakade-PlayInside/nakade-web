<?php
namespace Support\Controller;

use Support\Entity\LeagueManager;
use Support\Services\FormService;
use Support\Services\MailService;
use Support\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Nakade\Pagination\ItemPagination;
use Zend\View\Model\ViewModel;

/**
 * Class ManagerController
 *
 * @package Support\Controller
 */
class ManagerController extends AbstractController
{

    /**
     * for admin
     *
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $page = (int) $this->params()->fromRoute('id', 1);

        $total = $this->getMapper()->getMyLeagueManagersByPages($this->identity()->getId());
        $pagination = new ItemPagination($total);

        return new ViewModel(
            array(
                'paginator' => $pagination->getPagination($page),
                'managers' =>  $this->getMapper()->getMyLeagueManagersByPages(
                        $this->identity()->getId(),
                        $pagination->getOffset($page)
                ),
            )
        );
    }


    /**
     * @return array|ViewModel
     */
    public function addAction()
    {
        /* @var $form \Support\Form\LeagueManagerForm */
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

                /* @var $mail \Support\Mail\LeagueManagerNominationMail */
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

        /* @var $leagueManager \Support\Entity\LeagueManager */
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


        /* @var $leagueManager \Support\Entity\LeagueManager */
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
     * @return \Support\Mapper\ManagerMapper
     */
    private function getMapper()
    {
        /* @var $repo \Support\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::MANAGER_MAPPER);
    }

}

<?php
namespace Moderator\Controller;

use Moderator\Entity\Referee;
use Moderator\Services\FormService;
use Moderator\Services\MailService;
use Moderator\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Nakade\Pagination\ItemPagination;
use Zend\View\Model\ViewModel;

/**
 * Class RefereeController
 *
 * @package Moderator\Controller
 */
class RefereeController extends AbstractController
{
    const HOME = 'referee';

    //todo: overview for referees about other referees or Mailing other referees or Voting

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {

        $page = (int) $this->params()->fromRoute('id', 1);

        $total = $this->getMapper()->getRefereesByPages();
        $pagination = new ItemPagination($total);

        return new ViewModel(
            array(
                'referees' =>  $this->getMapper()->getRefereesByPages($pagination->getOffset($page)),
                'paginator' => $pagination->getPagination($page),
            )
        );
    }

    /**
     * @return array|ViewModel
     */
    public function addAction()
    {
        /* @var $form \Moderator\Form\RefereeForm */
        $form = $this->getForm(FormService::REFEREE_FORM);
        $referee = new Referee();
        $form->bindEntity($referee);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute(self::HOME);
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $referee = $form->getData();

                /* @var $mail \Moderator\Mail\RefereeNominationMail */
                $mail = $this->getMailService()->getMail(MailService::REFEREE_NOMINATION_MAIL);
                $mail->setUser($referee->getUser());
                $mail->sendMail($referee->getUser());

                $this->getMapper()->save($referee);
                $this->flashMessenger()->addSuccessMessage('New Referee nominated');

                return $this->redirect()->toRoute(self::HOME);
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

        /* @var $referee \Moderator\Entity\Referee */
        $referee = $this->getMapper()->getRefereeById($uid);
        if (!is_null($referee)) {
            $referee->setIsActive(false);
            $this->getMapper()->save($referee);
            $this->flashMessenger()->addSuccessMessage('Referee deactivated');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute(self::HOME);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function unDeleteAction()
    {
        //get param
        $uid  = $this->params()->fromRoute('id', null);

        /* @var $leagueManager \Moderator\Entity\Referee */
        $referee = $this->getMapper()->getRefereeById($uid);
        if (!is_null($referee)) {
            $referee->setIsActive(true);
            $this->getMapper()->save($referee);
            $this->flashMessenger()->addSuccessMessage('Referee activated');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute(self::HOME);
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

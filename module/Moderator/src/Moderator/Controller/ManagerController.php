<?php
namespace Moderator\Controller;

use Moderator\Entity\LeagueManager;
use Moderator\Services\FormService;
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

   /**
    * @return array|ViewModel
    */
    public function indexAction()
    {
        return new ViewModel(
            array(
              //  'paginator' => $paginator,
                'managers' =>  $this->getMapper()->getLeagueManager(),
            )
        );

    }

    /**
     * @return array|ViewModel
     */
    public function addAction()
    {
        /* @var $form \User\Form\UserForm */
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

                /* @var $mail \User\Mail\RegistrationMail */
            //    $mail = $this->getMailService()->getMail(MailService::REGISTRATION_MAIL);
            //    $mail->setUser($user);
            //    $mail->sendMail($user);

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
     * @return \Moderator\Mapper\ManagerMapper
     */
    private function getMapper()
    {
        /* @var $repo \Moderator\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::MANAGER_MAPPER);
    }

}

<?php
namespace User\Controller;

use User\Services\RepositoryService;
use User\Services\UserFormService;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Class ProfileController
 *
 * @package User\Controller
 */
class ProfileController extends AbstractController
{

    /**
     * Showing the user's profile
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $user = $this->getUser();

        return new ViewModel(
            array(
               'profile'    => $user,
               'name'       => $user->getName(),
               'username'   => $user->getUsername(),
           )
        );
    }

    /**
     * edit the birthday
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function birthdayAction()
    {
        /* @var $form \User\Form\BirthdayForm */
        $form = $this->getForm(UserFormService::BIRTHDAY_FORM);
        $user = $this->getUser();
        $form->bindEntity($user);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('profile');
            }

            $form->setData($postData);

            if ($form->isValid()) {
                $user = $form->getData();
                $this->getUserMapper()->save($user);

                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
            array (
               'form'    => $form
            )
        );

    }



    /**
     * edit the nickname
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function nickAction()
    {
        /* @var $form \User\Form\NickForm */
        $form = $this->getForm(UserFormService::NICK_FORM);
        $user = $this->getUser();
        $form->bindEntity($user);
        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('profile');
            }
            $form->setData($postData);

            if ($form->isValid()) {

                $user = $form->getData();
                $this->getUserMapper()->save($user);

                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
            array (
              'form'    => $form
            )
        );
    }

    /**
     * edit the KGS name
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function kgsAction()
    {
        /* @var $form \User\Form\KgsForm */
        $form = $this->getForm(UserFormService::KGS_FORM);
        $user = $this->getUser();
        $form->bindEntity($user);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('profile');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $user = $form->getData();
                $this->getUserMapper()->save($user);

                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
            array(
                'form'    => $form
            )
        );
    }

    /**
     * edit the email
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function emailAction()
    {
        /* @var $form \User\Form\EmailForm */
        $form = $this->getForm(UserFormService::EMAIL_FORM);
        $user = $this->getUser();
        $form->bindEntity($user);


        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('profile');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $user = $form->getData();
                $this->getUserMapper()->save($user);

                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
            array(
              'form'    => $form
            )
        );
    }

    /**
     * change the password. No email is send.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function passwordAction()
    {

        /* @var $form \User\Form\PasswordForm */
        $form = $this->getForm(UserFormService::PASSWORD_FORM);
        $user = $this->getUser();
        $form->bindEntity($user);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('profile');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $user = $form->getData();
                $this->getUserMapper()->save($user);

                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
            array(
              'form'    => $form
            )
        );
    }

    /**
     * change the password. No email is send.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function languageAction()
    {

        /* @var $form \User\Form\LanguageForm */
        $form = $this->getForm(UserFormService::LANGUAGE_FORM);
        $user = $this->getUser();
        $form->bindEntity($user);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('profile');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $user = $form->getData();
                $this->getUserMapper()->save($user);

                $identity = $this->getService()->getIdentity();
                $identity->setLanguage($user->getLanguage());

                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
            array(
                'form'    => $form
            )
        );
    }

    /**
     * @return \User\Mapper\UserMapper
     */
    private function getUserMapper()
    {
        /* @var $repo \User\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::USER_MAPPER);
    }

    /**
     * @return \User\Entity\User
     */
    private function getUser()
    {
        $profile = $this->identity();
        return $this->getUserMapper()->getUserById($profile->getId());
    }

}

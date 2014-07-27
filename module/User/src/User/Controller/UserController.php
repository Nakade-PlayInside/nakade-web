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

namespace User\Controller;

use Authentication\Password\PasswordGenerator;
use User\Verification\VerifyStringGenerator;
use User\Entity\User;
use User\Pagination\UserPagination;
use User\Services\UserFormService;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;
use User\Services\RepositoryService;

/**
 * Adminstrative user action for adding a new user, edit and resetting pwd.
 *
 */
class UserController extends AbstractController
{
    private $passwordService;

    /**
     * shows all users for adding and editing
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $page = (int) $this->params()->fromRoute('id', 1);

        /* @var $entityManager \Doctrine\ORM\EntityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $userPagination = new UserPagination($entityManager);
        $offset = (UserPagination::ITEMS_PER_PAGE * ($page -1));

        return new ViewModel(
            array(
              'users' => $this->getUserMapper()->getUserByPages($offset, UserPagination::ITEMS_PER_PAGE),
              'paginator' =>   $userPagination->getPagination($page),
            )
        );
    }

    /**
     * @return \Zend\Http\Response
     */
    public function detailsAction()
    {
        //get param
        $uid  = $this->params()->fromRoute('id', null);

        return new ViewModel(
            array(
                'user' => $this->getUserMapper()->getUserById($uid)
            )
        );
    }

    /**
     * add new user
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function addAction()
    {
        /* @var $form \User\Form\UserForm */
        $form = $this->getForm(UserFormService::USER_FORM);
        $user = new User();
        $form->bindEntity($user);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('user');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                //todo: proof isActive, isVerified, isAnonymous
                //todo: refactor mailService
                $user = $form->getData();
                $verifyString = VerifyStringGenerator::generateVerifyString();
                $password = $this->getPasswordService()->generatePassword();
                $pwdPlain = $this->getPasswordService()->getPlainPassword();

                $uri       = $request->getUri();
                $verifyUrl = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());

                $now  = new \DateTime();
                $user->setCreated($now);

                $dueTime  = sprintf('+ %s hour', 72);
                $dueDate = clone $now;
                $dueDate = $dueDate->modify($dueTime);

                $user->setVerifyString($verifyString);
                $user->setPassword($password);
                $user->setDue($dueDate);

                var_dump($user);die;

                $this->getUserMapper()->save($user);
                $this->flashMessenger()->addMessage('New user added');

                /* @var $mail \User\Mail\UserMail */
                $mail = $this->getMailFactory()->getMail('verify');
                $mail->setData($user);
                $mail->setRecipient($user->getEmail(), $user->getName());

                $mail->send();

                return $this->redirect()->toRoute('user');
            }
        }

        return new ViewModel(
            array(
              'form'    => $form
            )
        );
    }

    /**
     * edit a user
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function editAction()
    {

        //get param
        $uid  = $this->params()->fromRoute('id', null);

        /* @var $form \User\Form\UserForm */
        $form = $this->getForm(UserFormService::USER_FORM);
        $user=$this->getUserMapper()->getUserById($uid);
        $form->bindEntity($user);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();
            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('user');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $user = $form->getData();
                $date = new \DateTime();
                $user->setEdit($date);
                $this->getUserMapper()->save($user);

                $this->flashMessenger()->addSuccessMessage('User updated');

                return $this->redirect()->toRoute('user');
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
     * deactivate a user
     *
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        //get param
        $uid  = $this->params()->fromRoute('id', null);

        /* @var $user User */
        $user = $this->getUserMapper()->getUserById($uid);
        if (!is_null($user)) {
            $user->setActive(false);

            $this->getUserMapper()->save($user);
            $this->flashMessenger()->addSuccessMessage('User updated');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute('user');
    }

    /**
     * reactivate a user
     *
     * @return \Zend\Http\Response
     */
    public function unDeleteAction()
    {
        //get param
        $uid  = $this->params()->fromRoute('id', null);

        /* @var $user User */
        $user = $this->getUserMapper()->getUserById($uid);
        if (!is_null($user)) {
            $user->setActive(true);

            $this->getUserMapper()->save($user);
            $this->flashMessenger()->addSuccessMessage('User updated');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute('user');
    }

    /**
     * reset the user's password (send mail)
     *
     * @return \Zend\Http\Response
     */
    public function resetPasswordAction()
    {
        $uid  = $this->params()->fromRoute('id', null);
        $user=$this->getUserMapper()->getUserById($uid);
        $form = $this->getForm(UserFormService::CONFIRM_FORM);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $postData =  $request->getPost();
            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('user');
            }
            if (isset($postData['submit'])) {

                $date = new \DateTime();
                $user->setPwdChange($date);
                $password = PasswordGenerator::generatePassword(12);
                $encryptPwd = PasswordGenerator::encryptPassword($password);
                $user->setPassword($encryptPwd);

                $this->getUserMapper()->save($user);
                $this->flashMessenger()->addSuccessMessage('User updated');

                //todo: mail with new pwd
                return $this->redirect()->toRoute('user');
            }
        }

        return new ViewModel(
            array(
                'user' => $user,
                'form'=> $form,
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
     * @param mixed $passwordService
     */
    public function setPasswordService($passwordService)
    {
        $this->passwordService = $passwordService;
    }

    /**
     * @return \Nakade\Generators\PasswordGenerator
     */
    public function getPasswordService()
    {
        return $this->passwordService;
    }

}

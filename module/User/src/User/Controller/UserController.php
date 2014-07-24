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
use User\Business\VerifyStringGenerator;
use User\Entity\User;
use User\Services\UserFormService;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Adminstrative user action for adding a new user, edit and resetting pwd.
 *
 */
class UserController extends AbstractController
{

    /**
     * shows all users for adding and editing
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return new ViewModel(
            array(
              'users' => $this->getUserMapper()->getAllUser()
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

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('user');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                //todo: proof isActive, isVerified, isAnonymous
                //todo: refactor mailService
               // $user = $form->getData();
                $verifyString = VerifyStringGenerator::generateVerifyString();
                $password = PasswordGenerator::generatePassword(12);
                $encryptPwd = PasswordGenerator::encryptPassword($password);

                $uri       = $this->getRequest()->getUri();
                $verifyUrl = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());

                $now  = new \DateTime();
                $user->setCreated($now);

                $dueTime  = sprintf('+ %s hour', 72);
                $dueDate = clone $now;
                $dueDate = $dueDate->modify($dueTime);

                $user->setVerifyString($verifyString);
                $user->setPassword($encryptPwd);
                $user->setDue($dueDate);

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
     * reset the user's password (send mail)
     *
     * @return \Zend\Http\Response
     */
    public function resetPasswordAction()
    {

       //@todo: nachfrage double_opt passwort zurücksetzen?YN

       //get param
       $uid  = $this->params()->fromRoute('id', null);

       $data['uid'] = $uid;
       $data['request'] = $this->getRequest();
       $this->getService()->resetPassword($data);

       return $this->redirect()->toRoute('user');
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

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
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
     * @return \User\Mapper\UserMapper
     */
    private function getUserMapper()
    {
        /* @var $repo \User\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(\User\Services\RepositoryService::USER_MAPPER);
    }


}

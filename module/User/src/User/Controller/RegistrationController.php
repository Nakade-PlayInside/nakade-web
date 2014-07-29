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

use User\Entity\User;
use User\Services\MailService;
use User\Services\RepositoryService;
use User\Services\UserFormService;
use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Class RegistrationController
 *
 * @package User\Controller
 */
class RegistrationController extends AbstractController
{
    private $passwordService;

    /**
     * user registration
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        /* @var $form \User\Form\BaseForm */
        $form = $this->getForm(UserFormService::REGISTER_CLOSED_BETA_FORM);
        $user = new User();
        $form->bindEntity($user);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('login');
            }
            $form->setData($postData);
            if ($form->isValid()) {

                $user = $form->getData();
                if (isset($postData['code'])) {
                    //todo: coupon code used?
                    //todo: coupon code expired?
                    //todo: coupon code validation
                }

                if (!is_null($user)) {

                    /* @var $mail \User\Mail\CredentialsMail */
                    $mail = $this->getMailService()->getMail(MailService::CREDENTIALS_MAIL);
                    $mail->setUser($user);
                    $mail->sendMail($user);
                    $this->flashMessenger()->addSuccessMessage('Password Reset');

                    /*  $this->getRepository()
                          ->getMapper(RepositoryService::USER_MAPPER)
                          ->save($user);*/
                    $this->redirect()->toRoute('register', array('action' => 'success'));
                }


                return $this->redirect()->toRoute('forgot/failure');


            }
        }

        return new ViewModel(
            array(
                'form'    => $form
            )
        );
    }

    /**
     * Verification action. A direct link to this action is provided
     * in the user's verification mail.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function verifyAction()
    {

        $email          = $this->params()->fromQuery('email', null);
        $verifyString   = $this->params()->fromQuery('verify', null);


        //no params -> error
        if (!isset($email) || !isset($verifyString)) {
            $this->flashMessenger()->addErrorMessage('Params missing.');
            return $this->redirect()->toRoute('register', array('action' => 'error'));
        }

        /* @var $user \User\Entity\User */
        $user = $this->getRepository()
            ->getMapper(RepositoryService::USER_MAPPER)
            ->getUserByEmailAndVerifyString($email, $verifyString);

        if (is_null($user)) {
            $this->flashMessenger()->addErrorMessage('User Not Found.');
            $this->redirect()->toRoute('register', array('action' => 'error'));
        }

        if($user->isVerified()) {
            $this->flashMessenger()->addSuccessMessage('Email already verified!');
            $this->redirect()->toRoute('register', array('action' => 'success'));
        }

        if ($user->isDue()) {
            $user->setVerified(true);
            $this->getRepository()->getMapper(RepositoryService::USER_MAPPER)->save($user);
            $this->flashMessenger()->addSuccessMessage('Email Verified!');
            $this->redirect()->toRoute('register', array('action' => 'success'));
        }

        $this->flashMessenger()->addErrorMessage('Verification is overdue!');
        return new ViewModel();
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function forgotAction()
    {

        $form = $this->getForm(UserFormService::FORGOT_PASSWORD_FORM);
        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('login');
            }
            $form->setData($postData);
            if ($form->isValid()) {

                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);

                /* @var $user \User\Entity\User */
                $user = $this->getRepository()
                    ->getMapper(RepositoryService::USER_MAPPER)
                    ->getUserByEmail($data['email']);


                if (!is_null($user)) {
                    $password = $this->getPasswordService()->generatePassword();
                    $user->setPassword($password);
                    $pwdPlain = $this->getPasswordService()->getPlainPassword();
                    $user->setPasswordPlain($pwdPlain);
                    $date = new \DateTime();
                    $user->setPwdChange($date);

                    /* @var $mail \User\Mail\CredentialsMail */
                    $mail = $this->getMailService()->getMail(MailService::CREDENTIALS_MAIL);
                    $mail->setUser($user);
                    $mail->sendMail($user);
                    $this->flashMessenger()->addSuccessMessage('Password Reset');

                    /*  $this->getRepository()
                          ->getMapper(RepositoryService::USER_MAPPER)
                          ->save($user);*/
                    $this->redirect()->toRoute('register', array('action' => 'success'));
                }


                return $this->redirect()->toRoute('forgot/failure');


            }
        }

        return new ViewModel(
            array(
                'form'    => $form
            )
        );
    }

    /**
     * widget for user registration
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function showAction()
    {

        return new ViewModel(
            array()
        );
    }



    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function successAction()
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

<?php
namespace Authentication\Controller;

use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Authentication controller for login of registered users.
 * Controller is using Doctrine for data base access and customized
 * authentication.
 */
class AuthController extends AbstractController
{
    /**
     * If not loggedIn this action method shows a login form.
     *
     * @return ViewModel
     */
    public function loginAction()
    {

        //if already login, redirect to success page
       if ($this->getService()->hasIdentity()) {
           return $this->redirect()->toRoute('success');
       }

       /* @var $form \Authentication\Form\AuthForm */
       $form =  $this->getForm('auth');

        /* @var $session \Authentication\Session\FailureContainer */
       $session =  $this->getSession();
       $showCaptcha = $session->hasExceededAllowedAttempts();
       $form->setIsShowingCaptcha($showCaptcha);

       return new ViewModel(
           array(
               'form'      => $form,
               'messages'  => $this->flashmessenger()->getMessages()
           )
       );

    }

    /**
     * User authentication process. After stripping and validating form data,
     * the authentication adapter is requested with the given credentials. If
     * successfully authenticated, the identity is stored in the session.
     * If flag is set, session lifetime is 14d.
     *
     * @return \Zend\Http\Response
     */
    public function authenticateAction()
    {

        /* @var $form \Authentication\Form\AuthForm */
        $form =  $this->getForm('auth');

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $postData =  $request->getPost();
            $form->setData($postData);

            //proving valid data
            if ($form->isValid()) {

                $data = $form->getData();

                $identity   = $data['identity'];
                $password   = $data['password'];
                $rememberMe = $data['rememberMe'];

                //set values to adapter
                $this->getService()
                    ->getAdapter()
                    ->setIdentityValue($identity)
                    ->setCredentialValue($password);

                //set value to session storage
                $this->getService()
                    ->getStorage()
                    ->setRememberMe($rememberMe);

                /* @var $authResult \Zend\Authentication\Result */
                $authResult = $this->getService()->authenticate();

                //save message temporary into flash messenger
                foreach ($authResult->getMessages() as $message) {
                    $this->flashmessenger()->addMessage($message);
                }

                if ($authResult->isValid()) {

                    /* @var $session \Authentication\Session\FailureContainer */
                    $session =  $this->getSession();
                    $session->clear();
                    return $this->redirect()->toRoute('dashboard');
                }

                $this->$this->getSessionService()->addFailedAttempt();

            }
        }

        return $this->redirect()->toRoute('login');

    }

    /**
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {

        //clears session and destroys cookie
        $this->getService()->clearIdentity();

        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }
}

<?php
namespace Authentication\Controller;

use Authentication\Entity\Login;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Class AuthController
 *
 * @package Authentication\Controller
 */
class AuthController extends DefaultController
{
    /**
     * If not loggedIn this action method shows a login form.
     *
     * @return ViewModel
     */
    public function loginAction()
    {

       if ($this->getService()->hasIdentity()) {
           return $this->redirect()->toRoute('dashboard');
       }

        $form =  $this->getLoginForm();
        $login = new Login();
        $form->bindEntity($login);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $postData =  $request->getPost();
            $form->setData($postData);

            if ($form->isValid()) {

                /* @var $login \Authentication\Entity\Login */
                $login = $form->getData();

                $authResult = $this->authenticate($login);
                $this->getStorage()->setRememberMe($login->isRememberMe());

                if ($authResult->isValid()) {
                    $this->getFailureContainer()->clear();
                    return $this->redirect()->toRoute('dashboard');
                } else {
                    $this->getFailureContainer()->addFailedAttempt();
                    $this->flashMessenger()->addErrorMessage('Invalid Credentials');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }
        }

        return new ViewModel(
            array('form'  => $form,
            )
        );

    }

    /**
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        //clears session and destroys cookie
        $this->getService()->clearIdentity();
        $this->flashmessenger()->addSuccessMessage("You have been logged out");
        return $this->redirect()->toRoute('login');
    }



}

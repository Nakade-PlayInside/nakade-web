<?php
//module/Authentication/src/Authentication/Controller/AuthController.php
namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Authentication\Form\AuthForm;
use Zend\Authentication\AuthenticationService;
use Authentication\Session\FailureContainer;

/**
 * Authentication controller for login of registered users.
 * Controller is using Doctrine for data base access and customized
 * authentication.
 */
class AuthController extends AbstractActionController
{
    protected $_form;
    protected $_authservice;
    protected $_session;
    protected $_max_failed_attempts=5;
   
    /**
     * constructor initializes a form and the authentication service
     * 
     * @param \Zend\Authentication\AuthenticationService $service
     * @param \Authentication\Form\AuthForm $form
     */
    public function __construct(
            AuthenticationService $service,
            AuthForm $form,
            FailureContainer $container
            ) 
    {
        
        $this->_authservice = $service;
        $this->_form = $form;
        $this->_session = $container;
        
    }
   
    /**
     * gets the authentication service. 
     * 
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        return $this->_authservice;
    }
    
    /**
     * get the authentication form 
     * 
     * @return \Zend\Form\Form
     */
    public function getForm()
    {
        return $this->_form;
    }
    
    /**
     * sets the max authentication attempts 
     * 
     * @param int $attempts
     */
    public function setMaxAuthAttempts($attempts) 
    {
    
        if(is_numeric($attempts))
           $this->_max_failed_attempts = $attempts;
        
    }
    
    /**
     * If not loggedIn this action method shows a login form.
     * 
     * @return array
     */
    public function loginAction()
    {
        
       
        //if already login, redirect to success page 
       if ($this->getAuthService()->hasIdentity()){
           return $this->redirect()->toRoute('success');
       }
      
       $this->_session->addAuthFailure();  
    
       //show captcha after n failed auth attempts
       if($this->_session->getAuthFailure() > $this->_max_failed_attempts) {
           $this->_form->setShowCaptcha(true);
       }

       $this->_form->init();
       
       return new ViewModel( 
               
           array(
               'form'      => $this->getForm(),
               'messages'  => $this->flashmessenger()->getMessages()
           )
       );
         
    }
    
    /**
     * User authentication process. After stripping and validating form data,
     * the authentication adapter is requested with the given credentials. If
     * sucessfull, the identity is stored in the session. If the flag is set,
     * session lifetime is elonged to 14d.
     * 
     * @return Response
     */
    public function authenticateAction()
    {
       
        $form       = $this->getForm();
       
        $request = $this->getRequest();
        
        //proving if method is post
        if ($request->isPost()){
         
            $form->setData($request->getPost());
        
            //proving valid data
            if ($form->isValid()){
        
                $identity = $request->getPost('identity');
                $password = $request->getPost('password');
                $rememberMe = $request->getPost('rememberme');
                
                //set values to adapter
                $this->getAuthService()->getAdapter()
                               ->setIdentityValue($identity)
                               ->setCredentialValue($password);
        
                //set value to session storage
                $this->getAuthService()->getStorage()
                                       ->setRememberMe($rememberMe);
     
                //authentication
                $authResult = $this->getAuthService()->authenticate();
                
               
                //save message temporary into flashmessenger
                foreach($authResult->getMessages() as $message) {
                    $this->flashmessenger()->addMessage($message);
                }
       
                if ($authResult->isValid()) {
                    return $this->redirect()->toRoute('success');
                }
                //fraud protection
                else {
                    
                    
                  // $this->_test->write($this->_test->get()+1);  
                   /*@todo login attempts, IP, Datetime
                   * after some attempts captcha,
                   * further more deactivate account, set verified flag,
                   * new verify mail 
                   * 
                   */
                }
            }
        }    

        return $this->redirect()->toRoute('login');
    
    }
    
    /**
     * Destroys the session cookie from storage and clears the identity from
     * the authentication adapter. Redirecting to logIn.
     *   
     * @return Response
     */
    public function logoutAction()
    {
       
        //clears session and destroys cookie
        $this->getAuthService()->clearIdentity();
        
        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }
}

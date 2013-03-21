<?php
//module/Authentication/src/Authentication/Controller/AuthController.php
namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\ViewModel;

use Authentication\Model\User;

/**
 * Authentication controller for login of registered users.
 * Controller is using Doctrine and the Zend Annotation builder
 * for the form. 
 */
class AuthController extends AbstractActionController
{
    protected $form;
    protected $authservice;
   
   
    /**
     * As configured in Module.php the authService returns the authentication
     * adapter from doctrine. Using the specific Zend name of the service 
     * is supported and recognized by ZF2 View helper 
     * 
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = 
                    
                     $authAdapter = $this->getServiceLocator()
                     ->get('Zend\Authentication\AuthenticationService');
        }
        
        return $this->authservice;
    }
    
    
    
    /**
     * Using the annotation builder, a form is created from a model.
     * 
     * @return \Zend\Form\Form
     */
    public function getForm()
    {
        if (! $this->form) {
            $user       = new User();
            $builder    = new AnnotationBuilder();
            $this->form = $builder->createForm($user);
        }
        
        return $this->form;
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

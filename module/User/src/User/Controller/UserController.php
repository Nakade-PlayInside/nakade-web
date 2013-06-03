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
use Authentication\Entity\Credential;
use User\Form\UserForm;
use User\Mail\MyMail;
use User\Business\VerifyStringGenerator;
use User\Form\ProfileFieldSet;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class UserController extends AbstractActionController 
    implements MyServiceInterface
{
    
    protected $_service;
    
    public function getService()
    {
       return $this->_service;    
    }
    
    public function setService($service)
    {
        $this->_service = $service;
        return $this;
    }
    
    
    public function indexAction()
    {
        return new ViewModel(
            array(
              'users' => $this->getService()->getAllUser()
            )
        );
    }
    
    public function addAction()
    {
       
        $form = $this->getService()->getForm();
        
        if ($this->getRequest()->isPost()) {
            
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            $form->setData($postData);
          
          
            
            if ($form->isValid()) {
            
                $validatedData = $form->getData();
                
                $user = new User();
                $user->populate($validatedData['profile']);
                
                $data = $validatedData['credentials'];
                $data['verified']=0;
                $data['active']=1;
                $data['created']=0;
                $data['verifyString']='abc';
                
                $credential= new Credential();
                $credential->populate($data);
                
                $obj = VerifyStringGenerator::getInstance();
                var_dump($obj->generateVerifyString());
                
                new MyMail();
                
                //var_dump($credential);
               // var_dump($validatedData['profile']);
                return;
                return $this->redirect()->toRoute('user');
            }
            
          
        }
        
        
        return new ViewModel(
           array(
              'form'    => $form
           )
       );
       
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
    
    
}

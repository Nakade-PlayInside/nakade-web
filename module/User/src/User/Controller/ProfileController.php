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

use Zend\View\Model\ViewModel;
use User\Form;
use Nakade\Abstracts\AbstractController;

class ProfileController extends AbstractController 
{
    
    public function indexAction()
    {
        $profile = $this->identity();
        
        if($profile === null) {
            return $this->redirect()->toRoute('login');
        }
        
        return new ViewModel(
           array(
              'profile'    => $profile, 
              'name'       => $profile->getName(),
              'birthday'   => $profile->getBirthday(),
              'id'         => $profile->getId(), 
              'nick'       => $profile->getNickname(),  
              'username'   => $profile->getUsername(),  
              'email'      => $profile->getEmail(),   
           )
       );
    }
    
    public function birthdayAction()
    {
        $profile = $this->identity();
        
        if($profile === null) {
            return $this->redirect()->toRoute('login');
        }
        
        $form = $this->getForm('birthday');
        $form->bindEntity($profile);
        
        if ($this->getRequest()->isPost()) {
            
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            $form->setData($postData);
           
            if ($form->isValid()) {
           
                $data = $form->getData();
                //$this->getService()->editUser($data);
      
                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
           array(
              'form'    => $form
           )
       );
       
    }
    
    public function nickAction()
    {
        $profile = $this->identity();
        
        if($profile === null) {
            return $this->redirect()->toRoute('login');
        }
        
        $form = $this->getForm('nick');
        $form->bindEntity($profile);
        
        if ($this->getRequest()->isPost()) {
            
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            $form->setData($postData);
           
            if ($form->isValid()) {
           
                $data = $form->getData();
                //$this->getService()->editUser($data);
      
                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
           array(
              'form'    => $form
           )
       );
    }
    
    public function emailAction()
    {
        $profile = $this->identity();
        
        if($profile === null) {
            return $this->redirect()->toRoute('login');
        }
        
        $form = $this->getForm('email');
        $form->bindEntity($profile);
        
        if ($this->getRequest()->isPost()) {
            
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            $form->setData($postData);
           
            if ($form->isValid()) {
           
                $data = $form->getData();
                //$this->getService()->editUser($data);
      
                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
           array(
              'form'    => $form
           )
       );
    }
    
    public function passwordAction()
    {
        $profile = $this->identity();
        
        if($profile === null) {
            return $this->redirect()->toRoute('login');
        }
        
        $form = $this->getForm('password');
        
        if ($this->getRequest()->isPost()) {
            
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            $form->setData($postData);
           
            if ($form->isValid()) {
           
                $data = $form->getData();
                //$this->getService()->editUser($data);
      
                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
           array(
              'form'    => $form
           )
       );
    }
}

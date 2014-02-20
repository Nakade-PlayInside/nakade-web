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

use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * resetting the user's password if registered email is matching
 */
class ForgotController extends AbstractController 
{
    
    /**
     * Showing the new pwd input request
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $form = $this->getForm('forgot');
        if ($this->getRequest()->isPost()) {
            
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            
            //cancel
            if($postData['cancel']) {
                return $this->redirect()->toRoute('profile');
            }    
            
            $form->setData($postData);
           
            if ($form->isValid()) {
           
                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $data['request'] = $this->getRequest();
                
                if($this->getService()->forgotPassword($data)) {
                    return $this->redirect()->toRoute('forgot/success');
                }
                else { 
                    return $this->redirect()->toRoute('forgot/failure');
                }    
                
            }
        }

        return new ViewModel(
           array(
              'form'    => $form
           )
       );
    }
    
    /**
     * new credentials are set and send by mail to the given email adress
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function successAction()
    {
        return new ViewModel(array());
    }
    

    /**
     * email not found in the database.
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function failureAction()
    {
        return new ViewModel();
    }
}

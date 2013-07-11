<?php
namespace League\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * processing user input, in detail results and postponing
 * matches.
 * 
 */
class ResultController 
    extends AbstractActionController 
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
    
    
   
   /**
    * showing all open results of the actual season
    */
    public function indexAction()
    {
        
       return new ViewModel(
                
            array(
                'matches' =>  $this->getService()->getOpenResult()
            )
        );
       
    }
    
    /**
    * showing all results of the actual user. All open matches are indicated.
    */
    public function myresultAction()
    {
        $profile = $this->identity();
        
        if($profile === null) {
            return $this->redirect()->toRoute('login');
        }
       
        
       return new ViewModel(
                
            array(
               'matches' =>  $this->getService()
                                  ->getMyResult( $profile->getId() )
            )
        );
       
    }
    
    /**
    * showing open results of the actual user
    */
    public function myopenAction()
    {
        $profile = $this->identity();
        
        if($profile === null) {
            return $this->redirect()->toRoute('login');
        }
       
        
       return new ViewModel(
                
            array(
                'matches' => $this->getService()
                                  ->getMyOpenResult( $profile->getId() )
            )
        );
       
    }
    
    /**
    * Form for adding a result
    */
    public function addAction()
    {
        
        $pid  = (int) $this->params()->fromRoute('id', 0);
        $form = $this->getService()->setResultFormValues($pid);
       
        if ($this->getRequest()->isPost()) {
            
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            $this->getService()->prepareFormForValidation($form, $postData);
            
            //if validated data are saved to database
            if ($this->getService()->processResultData($form)) {
                  return $this->redirect()->toRoute('actual');
            }
        }
          
       return new ViewModel(
           array(
              'id'      => $pid, 
              'match'   => $this->getService()->getMatch(), 
              'form'    => $form
           )
       );
    }

    
}

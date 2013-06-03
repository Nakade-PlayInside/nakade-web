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
    * showing the top league standings
    */
    public function indexAction()
    {
       return new ViewModel(
                
            array(
                'title'   =>  $this->getService()->getOpenResultTitle(),
                'matches' =>  $this->getService()->getOpenResult()
            )
        );
       
    }
    
    public function addresultAction()
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

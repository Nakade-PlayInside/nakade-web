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
    
    /**
     * shows all open results to enter
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function openAction()
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
            
            //data to form for validating
            $form->setData($this->getRequest()->getPost());
            
            //set filter and validator dependant on values 
            $this->getService()->setResultFormValidators($form);
            
            
            if ($form->isValid()) {
             
                //process and save validated data
                $this->getService()->processResultData($form->getData());

                return $this->redirect()->toRoute('actual');
            }
        }
        
          
       return new ViewModel(
           array(
              'id'      => $pid, 
              'match'   => $this->getService()->getMatch($pid), 
              'form'    => $form
           )
       );
    }

    
}

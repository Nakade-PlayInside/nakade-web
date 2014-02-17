<?php
namespace Nakade\Abstracts;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Extending for having a service getter and setter
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class AbstractController extends AbstractActionController {
   
    protected $_service;
    protected $_formFactory;
    
    public function getService()
    {
       return $this->_service;    
    }
    
    public function setService($service)
    {
        $this->_service = $service;
        return $this;
    }
    
    public function getFormFactory()
    {
       return $this->_formFactory;    
    }
    
    public function setFormFactory(AbstractFormFactory $factory)
    {
        $this->_formFactory = $factory;
        return $this;
    }
    
    public function getForm($typ)
    {
       
        if(null===$this->_formFactory)
            return null;
         
        return $this->_formFactory->getForm($typ);
        
    }
}

?>

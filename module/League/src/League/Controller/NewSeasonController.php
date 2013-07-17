<?php
namespace League\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * League tables and schedules of the actual season.
 * Top league table is presented by the default action index.
 * ActionSesaonServiceFactory is needed to be set.
 *   
 * @author Holger Maerz <holger@nakade.de>
 */
class NewSeasonController 
    extends AbstractActionController implements MyServiceInterface
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
    * Default action showing up the Top League table
    * in a short and compact version. This can be used as a widget.  
    */
    public function indexAction()
    {
        $actual=$this->getService()->getActualSeason();
        $open = $this->getService()->getOpenMatches();
        $last = $this->getService()->getLastMatchDate();
        var_dump(count($this->getService()->getLastSeason()));
        return new ViewModel(
           array(
              'actual'     => $actual,
              'open'       => $open,
              'last'       => $last,
           
           )
        );
    }
    
   
   
    
}

<?php
namespace Message\Controller;

use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * League tables and schedules of the actual season.
 * Top league table is presented by the default action index.
 * ActionSeasonServiceFactory is needed to be set.
 *   
 * @author Holger Maerz <holger@nakade.de>
 */
class MessageController extends AbstractController
{
    
    /**
    * Default action showing up the Top League table
    * in a short and compact version. This can be used as a widget.  
    */
    public function indexAction()
    {
          
        return new ViewModel(
           array(
              //'title'     => $this->getService()->getTitle(),
                'messages'  => $this->getService()->getMessages(),
           
           )
        );
    }
    
   
    
   
    
}

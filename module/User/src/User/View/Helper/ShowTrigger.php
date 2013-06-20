<?php
namespace User\View\Helper;
/**
 * Determines the position of players. Usually the position is determined by 
 * the order of sorted parameters. 
 * Player who have not yet started in the league are given an 
 * even position.  
 */

use Zend\View\Helper\AbstractHelper;
 
class ShowTrigger extends AbstractHelper
{
    
    public function __invoke($uid, $active)
    {
        
        
        $action = $active?'delete':'undelete';
        $info   = $active?'deactivate':'activate';
        $img    = $active?'deactivate.png':'activate.png';
        
        return sprintf('<a title="%s" href="user/%s/%s">
            <img alt="%s" src="images/%s"></a>',
            $info,
            $action,       
            $uid, 
            $info,  
            $img
        );
       
    }
    
   
}

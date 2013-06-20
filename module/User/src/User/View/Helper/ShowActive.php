<?php
namespace User\View\Helper;
use Zend\View\Helper\AbstractHelper;

/**
 * shows an active/inactive image  
 */
class ShowActive extends AbstractHelper
{
    
    public function __invoke($active)
    {
        
        if($active) {
            return sprintf('<img alt="YES" src="images/active.png">');
        }    
        
        return sprintf('<img alt="NO" src="images/inactive.png">');
            
       
    }
    
   
}

<?php
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * shows an active/inactive image or an envelope if not expired  
 */
class ShowVerified extends AbstractHelper
{
    
    public function __invoke($active, $expired=false)
    {
        
        if($active) {
            return '<img alt="YES" src="images/active.png">';
        }    
        if($expired) {
            return '<img alt="DUE" src="images/mail.png">';
        }
        
        return '<img alt="NO" src="images/inactive.png">';
        
            
            
            
       
    }
    
   
}

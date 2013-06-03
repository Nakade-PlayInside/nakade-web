<?php
namespace User\View\Helper;
/**
 * Determines the position of players. Usually the position is determined by 
 * the order of sorted parameters. 
 * Player who have not yet started in the league are given an 
 * even position.  
 */

use Zend\View\Helper\AbstractHelper;
 
class Salutation extends AbstractHelper
{
    
    public function __invoke($sex)
    {
        
        $sex=strtolower($sex)=='m'?'male':'female';
        return sprintf('<img alt="%s" src="images/small_%s.png">', $sex, $sex);
            
       
    }
    
   
}

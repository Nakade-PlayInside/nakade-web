<?php
namespace League\View\Helper;
/**
 * Determines the player's name. If the player has a nickname set as well as 
 * wants to be anonym, the nickname is shown instead of the forname. 
 * 
 */

use Zend\View\Helper\AbstractHelper;
 
class DateFormat extends AbstractHelper
{
    
    /**
     * formats an MySQL DateTime string 
     * in Date format d.m.Y.
     * 
     * @param string $datetime
     * @return string
     */
    public function __invoke($datetime)
    {
        
         if($datetime===null)
             return $datetime;
         
         $time = strtotime($datetime);
         return date('d.m.Y' , $time);

    }
    
   
}

<?php
namespace User\View\Helper;
/**
 * Determines the player's name. If the player has a nickname set as well as 
 * wants to be anonym, the nickname is shown instead of the forname. 
 * 
 */

use Zend\View\Helper\AbstractHelper;
 
class Birthday extends AbstractHelper
{
    
    /**
     * formats an MySQL DateTime string 
     * in Date format d.m.Y.
     * 
     * @param string $datetime
     * @return string
     */
    public function __invoke($date)
    {
        if($date==null)
             return '';
         
         return $date->format('d.m.Y');

    }
    
   
}

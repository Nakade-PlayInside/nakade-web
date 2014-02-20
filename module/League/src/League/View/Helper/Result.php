<?php
namespace League\View\Helper;

use Zend\View\Helper\AbstractHelper;
use League\Entity\Match;

/**
 * View helper for getting a table-like result
 */
class Result extends AbstractHelper
{
    /**
     * get the result as usual for team games.
     * 2:0 for a win, 1-1 for a draw and 0-0 for suspended games.
     * 
     * @param Match $match
     * @return string
     */
    public function __invoke(Match $match)
    {
        $result=$match->getResultId();
        
        if(null ===$result)
            return '';
        
        if($this->isSuspended($result))
            return '0-0';
        
        if($this->isDraw($result))
            return '1-1';
        
        
        return ($match->getWinner()==$match->getBlack()? 2:0) . 
               "-" .
               ($match->getWinner()==$match->getWhite()? 2:0);
                    
     } 
        
           
    /**
     * is game suspended
     * 
     * @param int $result
     * @return bool
     */
    private function isSuspended($result) 
    {
        return $result == 5 ;
    }
    
    /**
     * is match a draw 
     * 
     * @param int $result
     * @return bool
     */
    private function isDraw($result) 
    {
        return $result == 3 ;
    }
    
   
   
}

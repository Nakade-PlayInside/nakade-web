<?php
namespace League\View\Helper;

use Zend\View\Helper\AbstractHelper;
use League\Entity\Match;

/**
 * View helper determining if the match was already played.
 */
class Open extends AbstractHelper
{
    /**
     * if the match was already played.
     * 
     * @param Match $match
     * @return bool
     */
    public function __invoke(Match $match)
    {
       
       if( null != $match->getResultId() )
            return false;
      
        $today = new \DateTime();
        return ($match->getDate() < $today);
    }
}

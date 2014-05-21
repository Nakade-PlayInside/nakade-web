<?php
namespace Season\View\Helper;

use Zend\View\Helper\AbstractHelper;
use League\Entity\Match;

/**
 * View helper for determining if the actual player
 * was playing the match given
 */
class Active extends AbstractHelper
{
    /**
     * if actual user was playing the match
     *
     * @param Match $match
     * @return bool
     */
    public function __invoke(Match $match)
    {
       if(null === $this->getView()->identity())
            return false;

       $id = $this->getView()->identity()->getId();

       return  $id == $match->getBlackId() ||
                $id == $match->getWhiteId();


     }





}

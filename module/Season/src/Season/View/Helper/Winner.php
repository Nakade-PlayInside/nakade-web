<?php
namespace Season\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Season\Entity\Match;

/**
 * View helper for underlining a winner
 */
class Winner extends AbstractHelper
{
    /**
     * if player was winning, a style is returned for making an underline.
     *
     * @param type $match
     * @return string
     */
    public function __invoke(Match $match, $userId)
    {
        if($match->getWinnerId() != $userId)
            return;

        if($this->isWin($match->getResultId()) ) {
            echo 'style="text-decoration:underline"';
        }

    }

    /**
     * is match result a win
     *
     * @param int $result
     * @return bool
     */
    private function isWin($result)
    {
        return $result == 1 || $result == 2 || $result == 4 ;
    }

}

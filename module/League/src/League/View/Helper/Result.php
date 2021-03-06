<?php
namespace League\View\Helper;

use Nakade\Result\ResultInterface;
use Zend\View\Helper\AbstractHelper;
use Season\Entity\Match;

/**
 * Class Result
 *
 * @package League\View\Helper
 */
class Result extends AbstractHelper implements ResultInterface
{
    /**
     * get the result as usual for team games.
     * 2:0 for a win, 1-1 for a draw and 0-0 for suspended games.
     *
     * @param Match $match
     *
     * @return string
     */
    public function __invoke(Match $match)
    {
        $result = '';
        if ($match->hasResult()) {

             if ($this->hasWinner($match)) {
                 $result = $this->getWinningResult($match);
             } else {
                 $result = $this->getNotWinningResult($match);
             }
        }
        return $result;

    }

    /**
     * @param Match $match
     *
     * @return bool
     */
    private function hasWinner(Match $match)
    {
        return $match->getResult()->getResultType()->getId() != self::SUSPENDED &&
            $match->getResult()->getResultType()->getId() != self::DRAW;
    }

    /**
     * @param Match $match
     *
     * @return string
     */
    private function getNotWinningResult(Match $match)
    {
        $result = '1-1';
        if ($match->getResult()->getResultType()->getId() == self::SUSPENDED) {
            $result = '0-0';
        }
        return $result;
    }

    /**
     * @param Match $match
     *
     * @return string
     */
    private function getWinningResult(Match $match)
    {
        $result = '2:0';
        if ($match->getResult()->getWinner() == $match->getWhite()) {
            $result = '0:2';
        }
        return $result;

    }

}

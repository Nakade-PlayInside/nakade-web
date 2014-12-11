<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Calculation;

use Nakade\Result\ResultInterface;
use Season\Entity\Match;

class PlayerStatsFactory {

    private $consecutiveWins = array();
    private $maxConsecutiveWins = array();
    private $userId;
    private $win = array();
    private $draw = array();
    private $defeat = array();
    private $matches = array();

    public function __construct(array $matches, $userId)
    {
        $this->userId = $userId;
        $this->matches = $this->filterPlayed($matches);

        /* @var $match \Season\Entity\Match */
        foreach ($matches as $match) {
            $this->evalResult($match);
        }
    }

    private function filterPlayed(array $matches)
    {
        $played = array();

        /* @var $match Match*/
        foreach($matches as $match) {
            if ($match->getResult()->getResultType()->getId() == ResultInterface::SUSPENDED) {
                continue;
            }
            $played[] = $match;
        }
        return $played;

    }

    private function evalResult(Match $match)
    {
        if ($match->getResult()->hasWinner() && $match->getResult()->getWinner()->getId() == $this->getUserId()) {
            $this->addWin($match);
        } else {
            $this->setMaxConsecutiveWins();
            $this->addDefeat($match);
            $this->addDraw($match);
        }
    }

    private function addWin(Match $match)
    {
        $this->consecutiveWins[] = $match;
        $this->win[]=$match;
    }

    private function addDraw(Match $match)
    {
        if ($match->getResult()->getResultType()->getId() == ResultInterface::DRAW) {
            $this->draw[] = $match;
        }
    }

    private function addDefeat(Match $match)
    {
        if ($match->getResult()->hasWinner() && $match->getResult()->getWinner()->getId() != $this->getUserId()) {
            $this->defeat[] = $match;
        }
    }


    private function setMaxConsecutiveWins()
    {
        if (count($this->consecutiveWins) >= count($this->maxConsecutiveWins)) {
            $this->maxConsecutiveWins = $this->consecutiveWins;
        }
        $this->resetConsecutiveWins();
    }

    private function resetConsecutiveWins()
    {
        $this->consecutiveWins = array();
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function getDefeat()
    {
        return $this->defeat;
    }

    /**
     * @return array
     */
    public function getDraw()
    {
        return $this->draw;
    }

    /**
     * @return array
     */
    public function getMaxConsecutiveWins()
    {
        return $this->maxConsecutiveWins;
    }

    /**
     * @return array
     */
    public function getWin()
    {
        return $this->win;
    }

    /**
     * @return array
     */
    public function getMatches()
    {
        return $this->matches;
    }

}
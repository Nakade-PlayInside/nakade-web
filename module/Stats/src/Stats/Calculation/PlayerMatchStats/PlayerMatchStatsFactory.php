<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Calculation\PlayerMatchStats;

use Nakade\Result\ResultInterface;
use Season\Entity\Match;

class PlayerMatchStatsFactory implements MatchTypeInterface, ResultInterface
{

    private $match;
    private $userId;

    /**
     * @param int $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }


    /**
     * @param Match $match
     *
     * @return bool
     */
    public function addMatch(Match $match)
    {
        $this->match =$match;

        if($this->isSuspended()) {
            return false;
        }

        $type = $this->getType();
        $this->evaluate($type);
        if (!$this->isWin()) {
            $this->resetConsecutiveWins();
        }

        return true;

    }

    /**
     * @return string
     */
    private function getType()
    {
        if ($this->isWin()) {
            return self::MATCH_WIN;
        }   elseif ($this->isDraw()) {
            return self::MATCH_DRAW;
        }
        return self::MATCH_DEFEAT;
    }

    /**
     * @param string $type
     */
    private function evaluate($type)
    {
        $match = $this->getMatch();

        switch ($type) {

            case self::MATCH_WIN:
                Wins::getInstance()->addMatch($match);
                ConsecutiveWins::getInstance()->addMatch($match);
                break;
            case self::MATCH_DEFEAT:
                Defeats::getInstance()->addMatch($match);
                break;
            case self::MATCH_DRAW:
                Draws::getInstance()->addMatch($match);
                break;
        }
        Played::getInstance()->addMatch($match);

    }

    /**
     * @return array
     */
    public function getData()
    {
            return array(
                'wins' => Wins::getInstance()->getMatches(),
                'loss' => Defeats::getInstance()->getMatches(),
                'draw' => Draws::getInstance()->getMatches(),
                'consecutiveWins' => ConsecutiveWins::getInstance()->getMatches(),
                'played' => Played::getInstance()->getMatches(),
            );
    }

    /**
     * reset in case of a loss or draw
     */
    private function resetConsecutiveWins()
    {
        ConsecutiveWins::getInstance()->resetMatches();
    }

    /**
     * @return bool
     */
    private function isWin()
    {
        return $this->getMatch()->getResult()->hasWinner() &&
            $this->getMatch()->getResult()->getWinner()->getId() == $this->getUserId();
    }

    /**
     * @return bool
     */
    private function isDraw()
    {
        return $this->getMatch()->getResult()->getResultType()->getId() == ResultInterface::DRAW;
    }

    /**
     * @return bool
     */
    private function isSuspended()
    {
        return $this->getMatch()->getResult()->getResultType()->getId() == ResultInterface::SUSPENDED;
    }


    /**
     * @return Match
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }


}
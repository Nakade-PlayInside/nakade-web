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

class PlayerMatchStatsFactory extends AbstractPlayerMatchStatsFactory implements MatchTypeInterface, ResultInterface
{

    private $match;
    private $userId;

    /**
     * @param int $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
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
        print_r($type);
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
                $this->getWins()->addMatch($match);
                $this->getConsecutiveWins()->addMatch($match);
                break;
            case self::MATCH_DEFEAT:
                $this->getDefeats()->addMatch($match);
                break;
            case self::MATCH_DRAW:
                $this->getDraws()->addMatch($match);
                break;
        }
        $this->getPlayed()->addMatch($match);

    }

    /**
     * @return array
     */
    public function getData()
    {
            return array(
                'wins' => $this->getWins()->getMatches(), //Wins::getInstance()->getMatches(),
                'loss' => $this->getDefeats()->getMatches(),
                'draw' => $this->getDraws()->getMatches(),
                'consecutiveWins' => $this->getConsecutiveWins()->getMatches(),
                'played' => $this->getPlayed()->getMatches(),
            );
    }

    /**
     * reset in case of a loss or draw
     */
    private function resetConsecutiveWins()
    {
        $this->getConsecutiveWins()->resetMatches();
    }

    /**
     * @return bool
     */
    public function isWin()
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
<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Calculation;

use League\Entity\Player;
use League\Entity\Result;
use Nakade\Result\ResultInterface;
use Season\Entity\Match;
use Stats\Entity\PlayerResult;
use User\Entity\User;

/**
 * Class ContingencyTableFactory
 *
 * @package Stats\Calculation
 */
class ContingencyTableFactory {

    const MATCH_NOT_PLAYED = '-:-';
    const MATCH_SUSPENDED = '0:0';
    const MATCH_DRAW = '1:1';
    const MATCH_WIN = '2:0';
    const MATCH_DEFEAT = '0:2';
    const MATCH_CROSS = 'x';

    private $players = array();
    private $matches = array();

    /**
     * @param array $players
     * @param array $matches
     */
    public function __construct(array $players, array $matches)
    {
        $this->players = $players;
        $this->matches = $matches;

    }

    /**
     * @return array
     */
    public function getTableData()
    {
        $data = array();

        /* @var $player \League\Entity\Player */
        foreach ($this->players as $player) {

            $results = $this->evalResults($player);
            $playerResult = new PlayerResult($player->getUser(), $results);
            $data[] = $playerResult;
        }
        return $data;
    }

    private function evalResults(Player $player)
    {
        $data = array();
        /* @var $opponent \League\Entity\Player */
        foreach ($this->players as $opponent) {

            if ($opponent == $player) {
                $data[] = self::MATCH_CROSS;
                continue;
            }

            $data[] = $this->getResult($player->getUser(), $opponent->getUser());
        }

        return $data;
    }

    private function getResult(User $playerA, User $playerB)
    {
        /* @var $match \Season\Entity\Match */
        foreach ($this->matches as $match) {
            if ($this->isMatch($match, $playerA, $playerB)) {
                return $this->evalResult($match->getResult(), $playerA);
            }
        }
        return self::MATCH_NOT_PLAYED;
    }

    private function isMatch(Match $match, User $playerA, User $playerB)
    {
        return ($match->getBlack() == $playerA && $match->getWhite() == $playerB) ||
            ($match->getBlack() == $playerB && $match->getWhite() == $playerA);
    }

    private function evalResult(Result $result, User $user)
    {
        if (empty($result)) {
            return self::MATCH_NOT_PLAYED;
        }
        return $this->evalWinner($result, $user);
    }

    private function evalWinner(Result $result, User $user)
    {
        if($result->hasWinner()) {
            return $this->evalWin($result, $user);
        }
        return $this->evalNotAWin($result);
    }


    private function evalWin(Result $result, User $user)
    {
        if ($user == $result->getWinner()) {
            return self::MATCH_WIN;
        }

        return self::MATCH_DEFEAT;
    }

    private function evalNotAWin(Result $result)
    {
        if ($result->getResultType() == ResultInterface::SUSPENDED) {
            return self::MATCH_SUSPENDED;
        }

        return self::MATCH_DRAW;
    }

}
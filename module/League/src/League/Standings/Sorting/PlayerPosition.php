<?php
namespace League\Standings\Sorting;

use League\Entity\Player;
/**
 * Class PlayerPosition
 *
 * @package League\Standings\Sorting
 */
class PlayerPosition implements SortingInterface
{
    private $playersInLeague;

    /**
     * @param array  $playersInLeague
     * @param string $sort
     */
    public function __construct(array $playersInLeague, $sort=self::BY_POINTS)
    {
        $this->playersInLeague = $playersInLeague;

        $method = 'positionBy' . ucfirst($sort);
        if (method_exists($this, $method)) {
            $this->setPosition($method);
        } else {
            $this->setPosition();
        }


    }

    /**
     * @return array
     */
    public function getPosition()
    {
       return $this->playersInLeague;
    }

    private function setPosition($method='positionByPoints')
    {
        $previous=null;
        /* @var  $player \League\Entity\Player */
        /* @var  $previous \League\Entity\Player */
        for ($i=0; $i < count($this->playersInLeague); $i++) {

            $player = $this->playersInLeague[$i];
            if ($i==0) {
                $player->setPosition($i+1);
               continue;
            }

            $previous = $this->playersInLeague[$i-1];
            if ($this->$method($previous, $player)) {
                $player->setPosition($i+1);
            }

        }
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function positionByName(Player $compA, Player $compB)
    {
        $tbA =  $compA->getUser()->getShortName();
        $tbB =  $compB->getUser()->getShortName();
        if ($tbA == $tbB) {
            return $this->positionByPoints($compA, $compB);
        }

        return (strcmp($tbA, $tbB)<0);
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function positionByPlayed(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesPlayed();
        $tbB =  $compB->getGamesPlayed();
        if ($tbA == $tbB) {
            return $this->positionByPoints($compA, $compB);
        }

        return ($tbA > $tbB);
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function positionByWin(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesWin();
        $tbB =  $compB->getGamesWin();
        if ($tbA == $tbB) {
            return $this->positionByPoints($compA, $compB);
        }

        return ($tbA > $tbB);
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function positionByDraw(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesDraw();
        $tbB =  $compB->getGamesDraw();
        if ($tbA == $tbB) {
            return $this->positionByPoints($compA, $compB);
        }

        return ($tbA > $tbB);
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function positionByLost(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesLost();
        $tbB =  $compB->getGamesLost();
        if ($tbA == $tbB) {
            return $this->positionByPoints($compA, $compB);
        }

        return ($tbA > $tbB);
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function positionByTb1(Player $compA, Player $compB)
    {
        $tbA =  $compA->getFirstTiebreak();
        $tbB =  $compB->getFirstTiebreak();
        if ($tbA == $tbB) {
            return $this->positionByPoints($compA, $compB);
        }

        return ($tbA > $tbB);
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function positionByTb2(Player $compA, Player $compB)
    {
        $tbA =  $compA->getSecondTiebreak();
        $tbB =  $compB->getSecondTiebreak();
        if ($tbA == $tbB) {
            return $this->positionByPoints($compA, $compB);
        }

        return ($tbA > $tbB);
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function positionByTb3(Player $compA, Player $compB)
    {
        $tbA =  $compA->getThirdTiebreak();
        $tbB =  $compB->getThirdTiebreak();
        if ($tbA == $tbB) {
            return $this->positionByPoints($compA, $compB);
        }

        return ($tbA > $tbB);
    }



    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function positionByPoints(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesPoints();
        $tbB =  $compB->getGamesPoints();
        if ($tbA == $tbB) {
            return $this->positionBySuspendedGames($compA, $compB);
        }
        return ($tbA > $tbB);
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    private function positionBySuspendedGames(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesPoints();
        $tbB =  $compB->getGamesPoints();
        if ($tbA == $tbB) {
            return $this->positionByFirstTiebreak($compA, $compB);
        }
        return ($tbA < $tbB);
    }


    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    private function positionByFirstTiebreak(Player $compA, Player $compB)
    {
           $tbA = $compA->getFirstTiebreak();
           $tbB = $compB->getFirstTiebreak();
           if ($tbA == $tbB) {
               return $this->positionBySecondTiebreak($compA, $compB);
           }

           return ($tbA > $tbB);
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    private function positionBySecondTiebreak(Player $compA, Player $compB)
    {
           $tbA = $compA->getSecondTiebreak();
           $tbB = $compB->getSecondTiebreak();
           if ($tbA == $tbB) {
               return $this->positionByThirdTiebreak($compA, $compA);
           }

           return ($tbA > $tbB);

    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    private function positionByThirdTiebreak(Player $compA, Player $compB)
    {

           $tbA = $compA->getThirdTiebreak();
           $tbB = $compB->getThirdTiebreak();
           if ($tbA == $tbB) {
               return $this->positionByGamesPlayed($compA, $compB);
           }
           return ($tbA > $tbB);
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    private function positionByGamesPlayed(Player $compA, Player $compB)
    {
        $tbA = $compA->getGamesPlayed();
        $tbB = $compB->getGamesPlayed();
        if ($tbA == $tbB) {
            return false;
        }

        return ($tbA > $tbB);
    }

}


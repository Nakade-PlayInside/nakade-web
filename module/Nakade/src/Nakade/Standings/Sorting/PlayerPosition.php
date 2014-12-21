<?php
namespace Nakade\Standings\Sorting;

use League\Entity\Player;
/**
 * Class PlayerPosition
 *
 * @package Nakade\Standings\Sorting
 */
class PlayerPosition implements SortingInterface
{
    /**
     * instance
     * @var object
     */
    private static $instance=null;

    /**
     * static for getting an instance of this class
     * @return PlayerPosition
     */
    public static function getInstance()
    {

        if (is_null(self::$instance)) {
            self::$instance=new self();
        }

        return self::$instance;
    }

    /**
     * @param array $playersInLeague
     * @param string $sort
     *
     * @return array
     */
    public function getStandings(array $playersInLeague, $sort=self::BY_POINTS)
    {
        //first sort by points for position
        $sorting = PlayerSorting::getInstance();
        $sorting->sorting($playersInLeague);

        $previous=null;
        $position = 1;
        /* @var  $player \League\Entity\Player */
        /* @var  $previous \League\Entity\Player */
        for ($i=0; $i < count($playersInLeague); $i++) {

            $player = $playersInLeague[$i];
            if ($i>0) {
                $previous = $playersInLeague[$i-1];
                if ($this->positionByPoints($previous, $player)) {
                    $position = $i+1;
                }
            }
            $player->setPosition($position);

        }

        //sort by user input
        $sorting->sorting($playersInLeague, $sort);
        return $playersInLeague;
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
     * Players looses by having more games suspended in a tiebreak after winning points
     *
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    private function positionBySuspendedGames(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesSuspended();
        $tbB =  $compB->getGamesSuspended();
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
               return $this->positionByThirdTiebreak($compA, $compB);
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


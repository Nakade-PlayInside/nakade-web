<?php
namespace Nakade\Standings\Sorting;

use \InvalidArgumentException;
use League\Entity\Player;

/**
 * Sorting an array of objects in a dependend order. After the first sorting
 * equals are sorted by points and its tiebreakers.
 * Next to it, all sorting patterns are provided as constants.
 * This is a singleton.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class PlayerSorting implements SortingInterface
{
    /**
     * instance
     * @var object
     */
    private static $instance=null;

    /**
     * static for getting an instance of this class
     * @return PlayerSorting
     */
    public static function getInstance()
    {

        if (is_null(self::$instance)) {
            self::$instance=new self();
        }

        return self::$instance;
    }

    /**
     * @param array  &$playersInLeague
     * @param string $sort
     *
     * @throws \InvalidArgumentException
     */
    public function sorting(&$playersInLeague, $sort=self::BY_POINTS)
    {
       $method = 'sortBy' . ucfirst($sort);
       if (!method_exists($this, $method)) {
           throw new InvalidArgumentException(sprintf('A unknown sorting type was provided: %s', $sort));
       }
       usort($playersInLeague, array($this, $method));
    }


    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function sortByName(Player $compA, Player $compB)
    {
           $nameA =  $compA->getUser()->getShortName();
           $nameB =  $compB->getUser()->getShortName();
           $result = strcmp($nameA, $nameB);

           if ($result == 0) {
               return $this->sortByPoints($compA, $compB);
           }

           return $result;
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function sortByPlayed(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesPlayed();
        $tbB =  $compB->getGamesPlayed();
        if ($tbA == $tbB) {
            return $this->sortByPoints($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function sortBySuspended(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesSuspended();
        $tbB =  $compB->getGamesSuspended();
        if ($tbA == $tbB) {
            return $this->sortByPoints($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function sortByWin(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesWin();
        $tbB =  $compB->getGamesWin();
        if ($tbA == $tbB) {
               return $this->sortByPoints($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function sortByLost(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesLost();
        $tbB =  $compB->getGamesLost();
        if ($tbA == $tbB) {
               return $this->sortByPoints($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function sortByDraw(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesDraw();
        $tbB =  $compB->getGamesDraw();
        if ($tbA == $tbB) {
               return $this->sortByPoints($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * user sorting by table
     *
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function sortByTb1(Player $compA, Player $compB)
    {
        $tbA =  $compA->getFirstTiebreak();
        $tbB =  $compB->getFirstTiebreak();
        if ($tbA == $tbB) {
            return $this->sortByPoints($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * user sorting by table
     *
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function sortByTb2(Player $compA, Player $compB)
    {
        $tbA =  $compA->getSecondTiebreak();
        $tbB =  $compB->getSecondTiebreak();
        if ($tbA == $tbB) {
            return $this->sortByPoints($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * user sorting by table
     *
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function sortByTb3(Player $compA, Player $compB)
    {
        $tbA =  $compA->getThirdTiebreak();
        $tbB =  $compB->getThirdTiebreak();
        if ($tbA == $tbB) {
            return $this->sortByPoints($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    public function sortByPoints(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesPoints();
        $tbB =  $compB->getGamesPoints();
        if ($tbA == $tbB) {
            return $this->sortBySuspendedGames($compA, $compB);
        }
        return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    private function sortBySuspendedGames(Player $compA, Player $compB)
    {
        $tbA =  $compA->getGamesSuspended();
        $tbB =  $compB->getGamesSuspended();
        if ($tbA == $tbB) {
            return $this->sortByFirstTiebreak($compA, $compB);
        }

        return ($tbA > $tbB)?1:-1;
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    private function sortByFirstTiebreak(Player $compA, Player $compB)
    {
           $tbA = $compA->getFirstTiebreak();
           $tbB = $compB->getFirstTiebreak();
           if ($tbA == $tbB) {
               return $this->sortBySecondTiebreak($compA, $compB);
           }

           return ($tbA>$tbB)?-1:1;
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    private function sortBySecondTiebreak(Player $compA, Player $compB)
    {
           $tbA = $compA->getSecondTiebreak();
           $tbB = $compB->getSecondTiebreak();
           if ($tbA == $tbB) {
                return $this->sortByThirdTiebreak($compA, $compB);
           }

           return ($tbA > $tbB)?-1:1;

    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    private function sortByThirdTiebreak(Player $compA, Player $compB)
    {
           $tbA = $compA->getThirdTiebreak();
           $tbB = $compB->getThirdTiebreak();
           if ($tbA == $tbB) {
               return $this->sortByGamesPlayed($compA, $compB);
           }
           return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Player $compA
     * @param Player $compB
     *
     * @return int
     */
    private function sortByGamesPlayed(Player $compA, Player $compB)
    {
        $tbA = $compA->getGamesPlayed();
        $tbB = $compB->getGamesPlayed();
        if ($tbA == $tbB) {
            return 0;
        }

        return ($tbA > $tbB)?-1:1;
    }

}


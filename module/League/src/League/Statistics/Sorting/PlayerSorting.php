<?php
namespace League\Statistics\Sorting;

use \InvalidArgumentException;
use League\Entity\Participants;

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
     * @param Participants $compA
     * @param Participants $compB
     *
     * @return int
     */
    public function sortByName(Participants $compA, Participants $compB)
    {
           $nameA =  $compA->getPlayer()->getShortName();
           $nameB =  $compB->getPlayer()->getShortName();
           $result = strcmp($nameA, $nameB);

           if ($result == 0) {
               return $this->sortByPoints($compA, $compB);
           }

           return $result;
    }

    /**
     * @param Participants $compA
     * @param Participants $compB
     *
     * @return int
     */
    public function sortBySuspended(Participants $compA, Participants $compB)
    {
        $tbA =  $compA->getGamesSuspended();
        $tbB =  $compB->getGamesSuspended();
        if ($tbA == $tbB) {
           return $this->sortByPoints($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Participants $compA
     * @param Participants $compB
     *
     * @return int
     */
    public function sortByPlayed(Participants $compA, Participants $compB)
    {
        $tbA =  $compA->getGamesPlayed();
        $tbB =  $compB->getGamesPlayed();
        if ($tbA == $tbB) {
            return $this->sortByPoints($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Participants $compA
     * @param Participants $compB
     *
     * @return int
     */
    public function sortByWin(Participants $compA, Participants $compB)
    {
        $tbA =  $compA->getGamesWin();
        $tbB =  $compB->getGamesWin();
        if ($tbA == $tbB) {
               return $this->sortByPoints($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Participants $compA
     * @param Participants $compB
     *
     * @return int
     */
    public function sortByLost(Participants $compA, Participants $compB)
    {
        $tbA =  $compA->getGamesLost();
        $tbB =  $compB->getGamesLost();
        if ($tbA == $tbB) {
               return $this->sortByPoints($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Participants $compA
     * @param Participants $compB
     *
     * @return int
     */
    public function sortByDraw(Participants $compA, Participants $compB)
    {
        $tbA =  $compA->getGamesDraw();
        $tbB =  $compB->getGamesDraw();
        if ($tbA == $tbB) {
               return $this->sortByPoints($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Participants $compA
     * @param Participants $compB
     *
     * @return int
     */
    public function sortByPoints(Participants $compA, Participants $compB)
    {
        $tbA =  $compA->getGamesPoints();
        $tbB =  $compB->getGamesPoints();
        if ($tbA == $tbB) {
            return $this->sortByFirstTiebreak($compA, $compB);
        }

        return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Participants $compA
     * @param Participants $compB
     *
     * @return int
     */
    public function sortByFirstTiebreak(Participants $compA, Participants $compB)
    {
           $tbA = $compA->getFirstTiebreak();
           $tbB = $compB->getFirstTiebreak();
           if ($tbA == $tbB) {
               return $this->sortBySecondTiebreak($compA, $compB);
           }

           return ($tbA>$tbB)?-1:1;
    }

    /**
     * @param Participants $compA
     * @param Participants $compB
     *
     * @return int
     */
    public function sortBySecondTiebreak(Participants $compA, Participants $compB)
    {
           $tbA = $compA->getSecondTiebreak();
           $tbB = $compB->getSecondTiebreak();
           if ($tbA == $tbB) {
               return $this->sortByThirdTiebreak($compA, $compA);
           }

           return ($tbA > $tbB)?-1:1;

    }

    /**
     * @param Participants $compA
     * @param Participants $compB
     *
     * @return int
     */
    public function sortByThirdTiebreak(Participants $compA, Participants $compB)
    {

           $tbA = $compA->getThirdTiebreak();
           $tbB = $compB->getThirdTiebreak();
           if ($tbA == $tbB) {
               return $this->sortByInitialState($compA, $compB);
           }
           return ($tbA > $tbB)?-1:1;
    }

    /**
     * @param Participants $compA
     * @param Participants $compB
     *
     * @return int
     */
    public function sortByInitialState(Participants $compA, Participants $compB)
    {
           $tbA = $compA->getGamesPlayed();
           $tbB = $compB->getGamesPlayed();
           if ($tbA == $tbB) {
               return 0;
           }

        return ($tbA > $tbB)?-1:1;
    }

}


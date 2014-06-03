<?php
namespace League\Entity;

use Doctrine\ORM\Mapping as ORM;
use User\Entity\User;

/**
 * Model for statistics and sorting of actual standings
 *
 * @package League\Entity
 */
class Player
{

    private $user;
    private $gamesPlayed;
    private $gamesSuspended;
    private $gamesWin;
    private $gamesLost;
    private $gamesDraw;
    private $gamesPoints;
    private $firstTiebreak;
    private $secondTiebreak;
    private $thirdTiebreak;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * @param int $noGames
     */
    public function setGamesPlayed($noGames)
    {
        $this->gamesPlayed = $noGames;

    }

    /**
     * @return int
     */
    public function getGamesPlayed()
    {
        return $this->gamesPlayed;
    }

    /**
     * @param int $noGames
     */
    public function setGamesSuspended($noGames)
    {
        $this->gamesSuspended = $noGames;
    }

    /**
     * @return int
     */
    public function getGamesSuspended()
    {
        return $this->gamesSuspended;
    }

    /**
     * @param int $noGames
     */
    public function setGamesDraw($noGames)
    {
        $this->gamesDraw = $noGames;
    }

    /**
     * @return int
     */
    public function getGamesDraw()
    {
        return $this->gamesDraw;
    }

    /**
     * @param int $noGames
     */
    public function setGamesWin($noGames)
    {
        $this->gamesWin = $noGames;
    }

    /**
     * @return int
     */
    public function getGamesWin()
    {
        return $this->gamesWin;
    }

    /**
     * @param int $noGames
     */
    public function setGamesLost($noGames)
    {
        $this->gamesLost = $noGames;
    }

    /**
     * @return int
     */
    public function getGamesLost()
    {
        return $this->gamesLost;
    }

    /**
     * @param int $points
     */
    public function setGamesPoints($points)
    {
        $this->gamesPoints = $points;
    }

    /**
     * @return int
     */
    public function getGamesPoints()
    {
        return $this->gamesPoints;
    }

    /**
     * @param int $points
     */
    public function setFirstTiebreak($points)
    {
        $this->firstTiebreak = $points;
    }

    /**
     * @return int
     */
    public function getFirstTiebreak()
    {
        return $this->firstTiebreak;
    }

    /**
     * setter of second tiebreak
     *
     * @param int $points
     */
    public function setSecondTiebreak($points)
    {
        $this->secondTiebreak = $points;
    }

    /**
     * @return int
     */
    public function getSecondTiebreak()
    {
        return $this->secondTiebreak;
    }

    /**
     * @param int $points
     */
    public function setThirdTiebreak($points)
    {
        $this->thirdTiebreak = $points;
    }

    /**
     * @return int
     */
    public function getThirdTiebreak()
    {
        return $this->thirdTiebreak;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * populating data as an array.
     * key of the array is getter methods name.
     *
     * @param array $data
     */

    public function exchangeArray($data)
    {
        if (isset($data['gamesPlayed'])) {
            $this->gamesPlayed = $data['gamesPlayed'];
        }
        if (isset($data['gamesPoints'])) {
            $this->gamesPoints = $data['gamesPoints'];
        }
        if (isset($data['gamesWin'])) {
            $this->gamesWin = $data['gamesWin'];
        }
        if (isset($data['gamesDraw'])) {
            $this->gamesDraw = $data['gamesDraw'];
        }
        if (isset($data['gamesLost'])) {
            $this->gamesLost = $data['gamesLost'];
        }
        if (isset($data['gamesSuspended'])) {
            $this->gamesSuspended = $data['gamesSuspended'];
        }
        if (isset($data['firstTiebreak'])) {
            $this->firstTiebreak = $data['firstTiebreak'];
        }
        if (isset($data['secondTiebreak'])) {
            $this->secondTiebreak = $data['secondTiebreak'];
        }
        if (isset($data['thirdTiebreak'])) {
            $this->thirdTiebreak = $data['thirdTiebreak'];
        }

    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}
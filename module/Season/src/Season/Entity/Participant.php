<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class Participant
 *
 * @package Season\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="participants")
 */
class Participant extends ParticipantModel
{
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
   * populating data as an array.
   * key of the array is getter methods name.
   *
   * @param array $data
   */

  public function exchangeArray($data)
  {
       return $data;

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
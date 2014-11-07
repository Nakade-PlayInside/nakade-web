<?php
namespace Stats\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class MatchStats
 *
 * @package Stats\Entity
 */
class MatchStats
{
    private $gamesPlayed;

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



}
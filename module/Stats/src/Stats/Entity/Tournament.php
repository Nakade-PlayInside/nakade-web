<?php
namespace Stats\Entity;

use Doctrine\ORM\Mapping as ORM;
use Season\Entity\League;

/**
 * Class TournamentStats
 *
 * @package Stats\Entity
 */
class Tournament
{
    private $tournament;
    private $matchStats;
    private $position;

    /**
     * @param MatchStats $matchStats
     */
    public function setMatchStats(MatchStats $matchStats)
    {
        $this->matchStats = $matchStats;
    }

    /**
     * @return MatchStats
     */
    public function getMatchStats()
    {
        return $this->matchStats;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param League $tournament
     */
    public function setTournament(League $tournament)
    {
        $this->tournament = $tournament;
    }

    /**
     * @return League
     */
    public function getTournament()
    {
        return $this->tournament;
    }


}
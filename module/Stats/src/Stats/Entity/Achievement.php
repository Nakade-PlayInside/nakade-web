<?php
namespace Stats\Entity;

use Doctrine\ORM\Mapping as ORM;
use Entities\User;
use Season\Entity\League;

/**
 * Class AchievementStats
 *
 * @package Stats\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="achievements")
 */
class Achievement
{
    private $id;
    private $league;
    private $player;
    private $position;
    private $egfRating;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $egfRating
     */
    public function setEgfRating($egfRating)
    {
        $this->egfRating = $egfRating;
    }

    /**
     * @return int
     */
    public function getEgfRating()
    {
        return $this->egfRating;
    }

    /**
     * @param League $league
     */
    public function setLeague(League $league)
    {
        $this->league = $league;
    }

    /**
     * @return League
     */
    public function getLeague()
    {
        return $this->league;
    }

    /**
     * @param User $player
     */
    public function setParticipant(User $player)
    {
        $this->player = $player;
    }

    /**
     * @return User
     */
    public function getPlayer()
    {
        return $this->player;
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


}
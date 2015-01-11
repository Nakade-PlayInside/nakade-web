<?php
namespace Stats\Entity;
use Season\Entity\Match;
use User\Entity\User;

/**
 * Class Rating
 * Prototype
 *
 * @package Stats\Entity
 */
class Rating
{
    private $user;
    private $egfRating;
    private $match;

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
     * @param Match $match
     */
    public function setMatch(Match $match)
    {
        $this->match = $match;
    }

    /**
     * @return Match
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }



}
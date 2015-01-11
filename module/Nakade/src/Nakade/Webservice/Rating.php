<?php
namespace Stats\Entity;
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
    private $rating;
    private $newRating;
    private $achievedResult;

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

    /**
     * @param int $newRating
     */
    public function setNewRating($newRating)
    {
        $this->newRating = $newRating;
    }

    /**
     * @return int
     */
    public function getNewRating()
    {
        return $this->newRating;
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param float $achievedResult
     */
    public function setAchievedResult($achievedResult)
    {
        $this->achievedResult = $achievedResult;
    }

    /**
     * @return float
     */
    public function getAchievedResult()
    {
        return $this->achievedResult;
    }



}
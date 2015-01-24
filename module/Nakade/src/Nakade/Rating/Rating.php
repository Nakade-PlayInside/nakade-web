<?php
namespace Nakade\Rating;

use User\Entity\User;
/**
 * Class Rating
 * Prototype
 *
 * @package Stats\Entity
 */
class Rating
{
    const MINIMUM_RATING = 100;
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

    /**
     * @return bool
     */
    public function hasRating()
    {
        return !is_null($this->rating);
    }

    /**
     * @return bool
     */
    public function hasResult()
    {
        return !is_null($this->achievedResult);
    }

    /**
     * @return bool
     */
    public function hasValidRating()
    {
        return $this->hasRating() && $this->rating >= self::MINIMUM_RATING;
    }

    /**
     * Returns true if player has a result and a valid rating. Both are necessary for calculating a new rating.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->hasResult() && $this->hasValidRating();
    }

    /**
     * populating data as an array.
     * key of the array is getter methods name.
     *
     * @param array $data
     */

    public function populate($data)
    {
        foreach ($data as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * @param array $data
     */
    public function exchangeArray($data)
    {
        $this->populate($data);

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
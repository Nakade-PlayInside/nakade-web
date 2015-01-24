<?php

namespace Nakade\Rating;

/**
 * Class EGFRating
 *
 * @package Nakade\Webservice
 */
class EGFRating
{
    const RATING_BASE = 100;
    const EPSILON = 0.016;

    private $ratingDifference;
    private $minWinningExpectancy;
    private $maxWinningExpectancy;
    private $conFactor;
    private $playerA;
    private $playerB;

    public function __construct(Rating $playerA, Rating $playerB)
    {
        $this->playerA = $playerA;
        $this->playerB = $playerB;
        $this->conFactor = new EGFConFactor();

    }

    /**
     * Performs the rating calculation and set the new rating. Use the getters for the results.
     * Returns false if rating calculation fails due to missing result or missing older rating.
     *
     * @return bool
     */
    public function doCalculation()
    {
        if ($this->getPlayerA()->isValid() && $this->getPlayerB()->isValid()) {

            $this->setNewRating($this->playerA);
            $this->setNewRating($this->playerB);

            return true;
        }
        return false;

    }

    /**
     * @return Rating
     */
    public function getStrongerPlayer()
    {
        if ($this->isPlayerAStronger()) {
            return $this->getPlayerA();
        }
        return $this->getPlayerB();

    }

    /**
     * @return Rating
     */
    public function getWeakerPlayer()
    {
        if ($this->isPlayerAStronger()) {
            return $this->getPlayerB();
        }
        return $this->getPlayerA();

    }

    /**
     * @return bool
     */
    private function isPlayerAStronger()
    {
        return $this->getPlayerA()->getRating() > $this->getPlayerB()->getRating();
    }

    /**
     * @param Rating &$player
     *
     * @return $this
     */
    private function setNewRating(Rating &$player)
    {

        $rating = $player->getRating();
        $result = $player->getAchievedResult();
        $winningExpectancy = $this->getWinningExpectancyByPlayer($player);

        $newRating = intval($rating + $this->getConFactor()->getCon($rating) * ($result - $winningExpectancy));
        $player->setNewRating($newRating);

        return $this;
    }

    /**
     * @param Rating $player
     *
     * @return float
     */
    private function getWinningExpectancyByPlayer(Rating $player)
    {
        if ($player === $this->getStrongerPlayer()) {
            return $this->getMaxWinningExpectancy();
        }
        return $this->getMinWinningExpectancy();
    }


    /**
     * @return float
     */
    public function getMaxWinningExpectancy()
    {
        if (is_null($this->maxWinningExpectancy)) {
            $this->maxWinningExpectancy = 1 - self::EPSILON - $this->getMinWinningExpectancy();
        }

        return $this->maxWinningExpectancy;
    }


    /**
     * @return float
     */
    public function getMinWinningExpectancy()
    {
        if (is_null($this->minWinningExpectancy)) {

            $minRating = $this->getWeakerPlayer()->getRating();
            $D = $this->getRatingDifference();
            $a = $this->getFactorA($minRating);
            $this->minWinningExpectancy =  1 / (exp($D/$a) + 1) - (self::EPSILON/2);
        }

        return $this->minWinningExpectancy;
    }

    /**
     * @return int
     */
    public function getRatingDifference()
    {
        if (is_null($this->ratingDifference)) {

            $diff = abs($this->getPlayerA()->getRating() - $this->getPlayerB()->getRating());
            $this->ratingDifference = intval($diff);
        }

        return $this->ratingDifference;
    }

    /**
     * @param int $rating
     *
     * @return int
     */
    public function getFactorA($rating)
    {
        return (int) (self::RATING_BASE * 2) - ($rating - self::RATING_BASE)/20;
    }

    /**
     * @return EGFConFactor
     */
    public function getConFactor()
    {
        return $this->conFactor;
    }



    /**
     * @return Rating
     */
    public function getPlayerA()
    {
        return $this->playerA;
    }

    /**
     * @return Rating
     */
    public function getPlayerB()
    {
        return $this->playerB;
    }



}

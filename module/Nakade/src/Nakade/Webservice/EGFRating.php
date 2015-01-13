<?php

namespace Nakade\Webservice;

use Stats\Entity\Rating;

/**
 * Class EGFRating
 *
 * @package Nakade\Webservice
 */
class EGFRating
{
    const RATING_BASE = 100;
    const EPSILON = 0.016;
    const CON_MAX = 10;
    const CON_MIN = 116;

    private $ratingDifference;
    private $minWinningExpectancy;
    private $maxWinningExpectancy;
    private $playerA;
    private $playerB;

    public function __construct(Rating $playerA, Rating $playerB)
    {
        $this->playerA = $playerA;
        $this->playerB = $playerB;

    }

    /**
     * Performs the rating calculation and set the new rating. Use the getters for the results.
     * Returns false if rating calculation fails due to missing result or missing older rating.
     *
     * @return bool
     */
    public function doCalculation()
    {

        if ($this->hasResult() && $this->hasRating()) {

            $this->setRatingDifference($this->getPlayerA(), $this->getPlayerB());

            $stronger = $this->getStrongerPlayer();
            $weaker   = $this->getWeakerPlayer();
            
            $this->setMinWinningExpectancy($weaker->getRating());
            $this->setMaxWinningExpectancy($this->getMinWinningExpectancy());

            $rating = $this->calculateRating($weaker, $this->getMinWinningExpectancy());
            $weaker->setNewRating($rating);

            $rating = $this->calculateRating($stronger, $this->getMaxWinningExpectancy());
            $stronger->setNewRating($rating);
            return true;
        }
        return false;

    }

    /**
     * @return bool
     */
    private function hasRating()
    {
        $ratingA = $this->getPlayerA()->getRating();
        $ratingB = $this->getPlayerB()->getRating();
        return !is_null($ratingA) && $ratingA >= 100 && !is_null($ratingB) && $ratingB >= 100;
    }

    /**
     * @return bool
     */
    private function hasResult()
    {
        return !is_null($this->getPlayerA()->getAchievedResult());
    }

    /**
     * @return Rating
     */
    private function getStrongerPlayer()
    {
        if ($this->isPlayerAStronger()) {
            return $this->getPlayerA();
        }
        return $this->getPlayerB();

    }

    /**
     * @return Rating
     */
    private function getWeakerPlayer()
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
     * @param Rating $player
     * @param int $winningExpectancy
     *
     * @return int
     */
    private function calculateRating(Rating $player, $winningExpectancy)
    {
        $rating = $player->getRating();
        $result = $player->getAchievedResult();

        return $rating + $this->getCon($rating) * ($result - $winningExpectancy);

    }

    /**
     * @param $minWinningExpectancy
     */
    private function setMaxWinningExpectancy($minWinningExpectancy)
    {
        $this->maxWinningExpectancy = 1 - self::EPSILON - $minWinningExpectancy;
    }

    /**
     * @return float
     */
    public function getMaxWinningExpectancy()
    {
        return $this->maxWinningExpectancy;
    }


    /**
     * @return float
     */
    public function getMinWinningExpectancy()
    {
        return $this->minWinningExpectancy;
    }

    /**
     * @param int $minRating
     */
    private function setMinWinningExpectancy($minRating)
    {
        $D = $this->getRatingDifference();
        $a = $this->getFactorA($minRating);

        $this->minWinningExpectancy = 1 / (exp($D/$a + 1)) - (self::EPSILON/2);

    }

    /**
     * @param Rating $playerA
     * @param Rating $playerB
     */
    private function setRatingDifference(Rating $playerA, Rating $playerB)
    {
        $ratingA = $playerA->getRating();
        $ratingB = $playerB->getRating();
        $diff = abs($ratingA-$ratingB);
        $this->ratingDifference = intval($diff);
    }

    /**
     * @return int
     */
    private  function getRatingDifference()
    {
        return $this->ratingDifference;
    }

    /**
     * @param int $rating
     *
     * @return int
     */
    private function getFactorA($rating)
    {
        return (int) (self::RATING_BASE * 2) - ($rating - self::RATING_BASE)/20;
    }


    /**
     * @param int $rating
     *
     * @return int
     */
    private function getCon($rating)
    {
        // formula is determined by linear regression using pairs of values and polynomial term of 3rd grade
        // see more http://www.arndt-bruenner.de/mathe/scripts/regr.htm

        if ($rating >= 2700) {
            return self::CON_MAX;
        } elseif ($rating <= 100) {
            return self::CON_MIN;
        }
        else {
            $con = round(-0.0502247 * $rating + 120.5131);
            return intval($con);
        }
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

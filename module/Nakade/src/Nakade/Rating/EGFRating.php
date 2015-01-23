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

        $newRating = intval($rating + $this->getCon($rating) * ($result - $winningExpectancy));
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

            $ratingA = $this->getPlayerA()->getRating();
            $ratingB = $this->getPlayerB()->getRating();
            $diff = abs($ratingA-$ratingB);
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
     *
     * @param int $rating
     *
     * @return int
     */
    public function getCon($rating)
    {
        /*todo: determine factor by linear regression using pairs of values and polynomial term of 3rd grade
          see http://www.arndt-bruenner.de/mathe/scripts/regr.htm, unfortunately it does not fit as proposed
          on the site
        */

        $lowerCon = $this->getNormalizedCon($rating);

        $upperRating = ceil($rating/100) *100;
        $upperCon = $this->getNormalizedCon($upperRating);
        $delta = $upperCon-$lowerCon;

        return intval($lowerCon + $delta * ($upperRating - $rating)/100);

    }

    /**
     * @param int $rating
     *
     * @return int
     */
    private function getNormalizedCon($rating)
    {
        // formula is determined by linear regression using pairs of values and polynomial term of 3rd grade
        // see more http://www.arndt-bruenner.de/mathe/scripts/regr.htm
        $normalizedRating = intval($rating/100) *100;


        switch ($normalizedRating) {
            case 100: $con=116;
                break;
            case 200: $con=110;
                break;
            case 300: $con=105;
                break;
            case 400: $con=100;
                break;
            case 500: $con=95;
                break;
            case 600: $con=90;
                break;
            case 700: $con=85;
                break;
            case 800: $con=80;
                break;
            case 900: $con=75;
                break;
            case 1000: $con=70;
                break;
            case 1100: $con=65;
                break;
            case 1200: $con=60;
                break;
            case 1300: $con=55;
                break;
            case 1400: $con=51;
                break;
            case 1500: $con=47;
                break;
            case 1600: $con=43;
                break;
            case 1700: $con=39;
                break;
            case 1800: $con=35;
                break;
            case 1900: $con=31;
                break;
            case 2000: $con=27;
                break;
            case 2100: $con=24;
                break;
            case 2200: $con=21;
                break;
            case 2300: $con=18;
                break;
            case 2400: $con=15;
                break;
            case 2500: $con=13;
                break;
            case 2600: $con=11;
                break;
            case 2700: $con=10;
                break;

            default:
                $con = 10;

                if ($rating > 2700) {
                    $con=self::CON_MAX;
                } elseif ($rating < 100) {
                    $con=self::CON_MIN;
                }

        }
        return $con;
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

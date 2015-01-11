<?php

namespace Nakade\Webservice;

use Season\Entity\Match;
use Stats\Entity\EGFData;
use Stats\Entity\Rating;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class EGDService
 *
 * @package Nakade\Webservice
 */
class EGFRatingService implements FactoryInterface
{
    const RATING_BASE = 100;
    const EPSILON = 0.016;
    const CON_MAX = 10;
    const CON_MIN = 116;

    private $player_A;
    private $player_B;


    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Nakade\Services\PlayersTableService
     */
    public function createService(ServiceLocatorInterface $services)
    {
        return $this;
    }


    public function startWithMatch(Match $match)
    {
        $player_A = new Rating();
        $player_A->setUser($match->getBlack());
        $player_A->setMatch($match);

        $player_B = new Rating();
        $player_B->setUser($match->getWhite());
        $player_B->setMatch($match);

        //getRating from database
        //if no rating then try get EGF Rating
        //if no EGF Rating then get average league rating? or entry rating?

    }

    /**
     * @param string $lastName
     * @param mixed $firstName
     *
     * @return bool|EGFData
     */
    public function getRating()
    {

        $result = $this->getResult();
        //$rating = $con * ($result - $Se($D)) + $oldRating;

        //
    }

    private function getResult()
    {

        if ($win=1) {
            return 1;
        }
        return 0.5; //jigo

    }

    private function getWinningExpectancyOfStrongerPlayer()
    {
        return 1 - self::EPSILON - $this->getWinningExpectancyOfWeakerPlayer();
    }

    private function getWinningExpectancyOfWeakerPlayer()
    {

        $D = $this->getRatingDifference(2000, 1900);
        $a = $this->getFactorA(1900);

        return 1 / (exp($D/$a +1)) - (self::EPSILON/2);

    }

    /**
     * @param int $ratingA
     * @param int $ratingB
     *
     * @return int
     */
    private function getRatingDifference($ratingA, $ratingB)
    {
        $diff = abs($ratingA-$ratingB);
        return intval($diff);
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


}

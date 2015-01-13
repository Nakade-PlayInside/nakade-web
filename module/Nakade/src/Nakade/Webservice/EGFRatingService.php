<?php

namespace Nakade\Webservice;

use Season\Entity\Match;
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

        $result = new EGFResult($match->getResult());

        $playerA = new Rating();
        $playerA->setUser($match->getBlack());
        $achievedResult = $result->getAchievedResult($match->getBlack());
        $playerA->setAchievedResult($achievedResult);//win

        $playerB = new Rating();
        $playerB->setUser($match->getWhite());
        $achievedResult = $result->getAchievedResult($match->getWhite());
        $playerB->setAchievedResult($achievedResult);//loss

        $rating = new EGFRating($playerA, $playerB);
        $rating->doCalculation();

        $rating->getPlayerA();
        $rating->getPlayerB();

    }


}

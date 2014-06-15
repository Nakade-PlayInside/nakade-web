<?php

namespace League\Services;

use League\Voter\MatchVoter;
use Season\Entity\Match;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class MatchVoterService implements FactoryInterface
{

    private $authenticationService;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $this->authenticationService = $services->get('Zend\Authentication\AuthenticationService');
        return $this;
    }

    /**
     * @param Match $match
     *
     * @return bool
     */
    public function isAllowed(Match $match)
    {
        $voter = new MatchVoter($match, $this->authenticationService);
        return $voter->isAllowed();
    }

}

<?php
namespace League\Voter;

use User\Entity\User;
use Season\Entity\Match;
use Permission\Entity\RoleInterface;
use Zend\Authentication\AuthenticationService;

/**
 * Class MatchVoter
 *
 * @package League\Voter
 */
class MatchVoter implements RoleInterface
{
    private $authenticationService;
    private $match;

    /**
     * @param Match                 $match
     * @param AuthenticationService $auth
     */
    public function __construct(Match $match, AuthenticationService $auth)
    {
        $this->authenticationService = $auth;
        $this->match = $match;
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        return !$this->getMatch()->hasResult() && $this->isAllowedUser();
    }

    /**
     * @return bool
     */
    private function isAllowedUser()
    {
        return ($this->isAdmin() || $this->isMatchPlayer());
    }

    /**
     * @return bool
     */
    private function isMatchPlayer()
    {
        $user = $this->getUser();
        if (is_null($user)) {
            return false;
        }
        return ($this->getMatch()->getBlack()->getId() == $user->getId() ||
            $this->getMatch()->getWhite()->getId() == $user->getId());
    }

    /**
     * @return bool
     */
    private function isAdmin()
    {
        $user = $this->getUser();
        if (is_null($user)) {
            return false;
        }
        return self::ROLE_ADMIN == $user->getRole();
    }

    /**
     * @return AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /**
     * @return Match
     */
    public function getMatch()
    {
        return $this->match;
    }


    /**
     * @return User|null
     */
    private function getUser()
    {
        $authService = $this->getAuthenticationService();
        if (!$authService->hasIdentity()) {
            return null;
        }
        return $authService->getIdentity();
    }

}


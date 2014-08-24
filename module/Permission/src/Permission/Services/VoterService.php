<?php

namespace Permission\Services;

use Permission\Entity\RoleInterface;
use Permission\Service\VoteInterface;
use Support\Services\RepositoryService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class VoterService
 *
 * @package Permission\Services
 */
class VoterService implements FactoryInterface, RoleInterface, VoterInterface
{
    private $mapper;
    private $authService;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $repository \Support\Services\RepositoryService */
        $repository = $services->get('Support\Services\RepositoryService');
        $this->mapper = $repository->getMapper(RepositoryService::MANAGER_MAPPER);
        $this->authService = $services->get('Zend\Authentication\AuthenticationService');

        return $this;
    }


    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->getRole() == self::ROLE_ADMIN;
    }

    /**
     * @return bool
     */
    public function isModerator()
    {
        return $this->isAdmin() || $this->getRole() == self::ROLE_MODERATOR;
    }

    /**
     * @return bool
     */
    public function isMember()
    {
        return $this->isModerator() || $this->getRole() == self::ROLE_MEMBER;
    }

    /**
     * @return bool
     */
    public function isUser()
    {
        return $this->isMember() || $this->getRole() == self::ROLE_USER;
    }

    /**
     * @return bool
     */
    public function isGuest()
    {
        return $this->getAuthService()->hasIdentity();
    }

    /**
     * @return bool
     */
    public function isLeagueOwner()
    {
        return $this->getAuthority() == self::ROLE_LEAGUE_OWNER;
    }

    /**
     * @return bool
     */
    public function isLeagueManager()
    {
        return $this->isLeagueOwner() || $this->getAuthority() == self::ROLE_LEAGUE_MANAGER;
    }

    /**
     * @return bool
     */
    public function isReferee()
    {
        return $this->getReferee() == self::ROLE_REFEREE;
    }

    /**
     * is admin, referee or league manager
     *
     * @return bool
     */
    public function isManager()
    {
        return $this->isLeagueManager() || $this->isModerator() || $this->isReferee();
    }

    /**
     * @return string
     */
    private function getReferee()
    {
        $role = self::DEFAULT_ROLE;

        if ($this->getAuthService()->hasIdentity()) {
            /* @var $identity \User\Entity\User */
            $identity = $this->getAuthService()->getIdentity();

            if ($this->getMapper()->isReferee($identity->getId())) {
                $role = self::ROLE_REFEREE;
            }
        }

        return $role;
    }


    /**
     * @return string
     */
    private function getAuthority()
    {
        $role = self::DEFAULT_ROLE;

        if ($this->getAuthService()->hasIdentity()) {
            /* @var $identity \User\Entity\User */
            $identity = $this->getAuthService()->getIdentity();

            if ($this->getMapper()->isOwner($identity->getId())) {
                $role = self::ROLE_LEAGUE_OWNER;
            } elseif ($this->getMapper()->isLeagueManager($identity->getId())) {
                $role = self::ROLE_LEAGUE_MANAGER;
            }
        }

        return $role;
    }

    /**
     * @return string
     */
    private function getRole()
    {
        $role = self::DEFAULT_ROLE;
        if ($this->getAuthService()->hasIdentity()) {
            /* @var $identity \User\Entity\User */
            $identity = $this->getAuthService()->getIdentity();
            $role = $identity->getRole();
        }

        return $role;
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    private function getAuthService()
    {
        return $this->authService;
    }

    /**
     * @return \Support\Mapper\ManagerMapper
     */
    private function getMapper()
    {
        return $this->mapper;
    }






}

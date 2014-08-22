<?php

namespace Permission\Services;

use Permission\Entity\RoleInterface;
use Support\Services\RepositoryService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class VoterService
 *
 * @package Permission\Services
 */
class VoterService implements FactoryInterface, RoleInterface
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



    public function isAdmin()
    {
        return $this->getRole() == self::ROLE_ADMIN;
    }

    public function isModerator()
    {
        return $this->isAdmin() || $this->getRole() == self::ROLE_MODERATOR;
    }

    public function isMember()
    {
        return $this->isModerator() || $this->getRole() == self::ROLE_MEMBER;
    }


    public function isUser()
    {
        return $this->isMember() || $this->getRole() == self::ROLE_USER;
    }

    public function isGuest()
    {
        return $this->getAuthService()->hasIdentity();
    }

    public function isLeagueOwner()
    {
        return $this->getAuthority() == self::ROLE_LEAGUE_OWNER;
    }

    public function isLeagueManager()
    {
        return $this->isLeagueOwner() || $this->getAuthority() == self::ROLE_LEAGUE_MANAGER;
    }

    public function isReferee()
    {
        return $this->getReferee() == self::ROLE_REFEREE;
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

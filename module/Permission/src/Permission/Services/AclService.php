<?php

namespace Permission\Services;

use Permission\Entity\RoleInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

/**
 * Class AclService
 *
 * @package Permission\Services
 */
class AclService implements FactoryInterface, RoleInterface
{
    private $acl;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');

        //configuration
        $resources = isset($config['Permission']['resources']) ?
            $config['Permission']['resources'] : array();

        $this->addRoles();
        $this->addResources($resources);

        return $this;

    }

    /**
     * @return \Zend\Permissions\Acl\Acl
     */
    public function getAcl()
    {
        if (is_null($this->acl)) {
            $this->acl = new Acl();
        }
        return $this->acl;
    }

    /**
     * add roles using role interface
     */
    public function addRoles()
    {
        $acl = $this->getAcl();
        //add roles ..
        $acl->addRole(new Role(self::DEFAULT_ROLE));
        $acl->addRole(new Role(self::ROLE_GUEST), self::DEFAULT_ROLE);
        $acl->addRole(new Role(self::ROLE_USER), self::ROLE_GUEST);
        $acl->addRole(new Role(self::ROLE_MEMBER), self::ROLE_USER);
        $acl->addRole(new Role(self::ROLE_MODERATOR), self::ROLE_MEMBER);
        $acl->addRole(new Role(self::ROLE_ADMIN), self::ROLE_MODERATOR);

        //additional roles -> look for ticket module in default mapper for role assignment
        $acl->addRole(new Role(self::ROLE_LEAGUE_MANAGER), self::ROLE_USER);
        $acl->addRole(new Role(self::ROLE_LEAGUE_OWNER), self::ROLE_USER);
        $acl->addRole(new Role(self::ROLE_REFEREE), self::ROLE_MEMBER);
    }

    /**
     * @param array $resources
     */
    public function addResources(array $resources)
    {
        $acl = $this->getAcl();
        foreach ($resources as $resource => $role) {
            $acl->addResource(new Resource($resource));
            $acl->allow($role, $resource);
        }
    }

}

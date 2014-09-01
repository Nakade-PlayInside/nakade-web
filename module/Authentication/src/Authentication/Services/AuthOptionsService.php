<?php
namespace Authentication\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Options\Authentication as AuthOptions;

/**
 * Class AuthOptionsService
 *
 * @package Authentication\Services
 */
class AuthOptionsService implements FactoryInterface
{


    /**
     * @param ServiceLocatorInterface $services
     *
     * @return AuthOptions
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');

        //configuration options as set in module.config
        $options = $config['doctrine']['authentication']['orm_default'] ?
            $config['doctrine']['authentication']['orm_default'] : null;

        if (is_null($options)) {
            throw new \RuntimeException('ORM default options are not found.');
        }

        //EntityManager for database access by doctrine
        if (!$services->has('Doctrine\ORM\EntityManager')) {
            throw new \RuntimeException('Entity manager is not found.');
        }
        $entityManager = $services->get('Doctrine\ORM\EntityManager');

        //auth Options instance and setting the entityManager
        $authOptions = new AuthOptions($options);
        $authOptions->setObjectManager($entityManager);

        return $authOptions;

    }

}



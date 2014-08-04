<?php

namespace Message\Services;

use Message\Mapper\MessageMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use RuntimeException;

/**
 * Class RepositoryService
 *
 * @package Message\Services
 */
class RepositoryService implements FactoryInterface
{
    const MESSAGE_MAPPER = 'message';

    private $entityManager;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Zend\ServiceManager\FactoryInterface
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        //EntityManager for database access by doctrine
        $this->entityManager = $services->get('Doctrine\ORM\EntityManager');

        if (null === $this->entityManager) {
            throw new RuntimeException(
                sprintf('Entity manager could not be found in service.')
            );
        }

        return $this;
    }


    /**
     * @param string $typ
     *
     * @return \Nakade\Abstracts\AbstractMapper
     *
     * @throws RuntimeException
     */
    public function getMapper($typ)
    {

        switch (strtolower($typ)) {

            case self::MESSAGE_MAPPER:
                $mapper = new MessageMapper();
                break;


            default:
                throw new RuntimeException(
                    sprintf('An unknown mapper type was provided.')
                );
        }

        $mapper->setEntityManager($this->entityManager);

        return $mapper;
    }

}

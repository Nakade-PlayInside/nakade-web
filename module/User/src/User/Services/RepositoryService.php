<?php
namespace User\Services;

use Nakade\RepositoryServiceInterface;
use User\Mapper\UserMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Doctrine\ORM\EntityManager;

/**
 * Class RepositoryService
 *
 * @package User\Services
 */
class RepositoryService implements FactoryInterface, RepositoryServiceInterface
{

    const USER_MAPPER = 'user';
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
        /* @var $entityManager \Doctrine\ORM\EntityManager  */
        $entityManager = $services->get('Doctrine\ORM\EntityManager');

        if (is_null($entityManager)) {
            throw new \RuntimeException(
                sprintf('Entity manager could not be found in service.')
            );
        }
        $this->setEntityManager($entityManager);

        return $this;
    }


    /**
     * @param string $typ
     *
     * @return \Nakade\Abstracts\AbstractMapper
     *
     * @throws \RuntimeException
     */
    public function getMapper($typ)
    {
        switch (strtolower($typ)) {

           case self::USER_MAPPER:
               $repository = new UserMapper();
               break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown mapper type was provided.')
               );
        }

        $entityManager = $this->getEntityManager();
        $repository->setEntityManager($entityManager);
        return $repository;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

}

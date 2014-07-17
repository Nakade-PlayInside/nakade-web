<?php

namespace Blog\Services;

use Blog\Mapper\BlogMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Doctrine\ORM\EntityManager;

/**
 * Class RepositoryService
 *
 * @package Blog\Services
 */
class RepositoryService implements FactoryInterface
{

    const BLOG_MAPPER = 'blog';
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
        $entityManager = $services->get('doctrine.entitymanager.orm_default');

        if (is_null($entityManager)) {
            throw new \RuntimeException(
                sprintf('Entity manager could not be found in service.')
            );
        }

        $config = $services->get('config');
        if (!isset($config['blog_database_connection'])) {
            throw new \RuntimeException(
                "Missing blog_database_connection configuration values."
            );
        }

        $connection = $config['blog_database_connection'];
        $entityManager = $entityManager->create($connection, $entityManager->getConfiguration());
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

           case self::BLOG_MAPPER:
               $repository = new BlogMapper();
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

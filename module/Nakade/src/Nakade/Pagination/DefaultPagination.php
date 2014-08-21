<?php
namespace Nakade\Pagination;

use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;


/**
 * Class DefaultPagination
 *
 * @package Nakade\Pagination
 */
abstract class DefaultPagination implements PaginationInterface
{
    protected $paginator;
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        $ormPagination = new ORMPaginator($this->getQuery());
        $adapter = new DoctrineAdapter($ormPagination);

        $this->paginator = new Paginator($adapter);
        $this->paginator->setDefaultItemCountPerPage(self::ITEMS_PER_PAGE);
    }

    /**
     * @return QueryBuilder
     */
    abstract protected function getQuery();

    /**
     * @return string
     */
    abstract protected function getEntityName();

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return Paginator
     */
    protected function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * @param int $page
     *
     * @return Paginator
     */
    public function getPagination($page)
    {
        return $this->getPaginator()->setCurrentPageNumber($page);
    }

}


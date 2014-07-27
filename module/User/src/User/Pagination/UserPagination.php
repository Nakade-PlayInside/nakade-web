<?php
namespace User\Pagination;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Doctrine\ORM\EntityManager;

/**
 * Class UserPagination
 *
 * @package User\Pagination
 */
class UserPagination
{
    const ITEMS_PER_PAGE    = 10;
    private $paginator;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $repository = $entityManager->getRepository('User\Entity\User');

        $ormPagination = new ORMPaginator($repository->createQueryBuilder('user'));
        $adapter = new DoctrineAdapter($ormPagination);

        $this->paginator = new Paginator($adapter);
        $this->paginator->setDefaultItemCountPerPage(self::ITEMS_PER_PAGE);
    }

    /**
     * @param int $page
     *
     * @return Paginator
     */
    public function getPagination($page)
    {
        return $this->paginator->setCurrentPageNumber($page);
    }

}


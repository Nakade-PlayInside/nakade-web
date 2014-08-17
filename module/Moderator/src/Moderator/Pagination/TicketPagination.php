<?php
namespace Moderator\Pagination;

use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Doctrine\ORM\EntityManager;


/**
 * Class TicketPagination
 *
 * @package Moderator\Pagination
 */
class TicketPagination
{

    const ITEMS_PER_PAGE = 10;
    private $paginator;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $repository = $entityManager->getRepository('Moderator\Entity\SupportRequest');

        $ormPagination = new ORMPaginator($repository->createQueryBuilder('ticket'));
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


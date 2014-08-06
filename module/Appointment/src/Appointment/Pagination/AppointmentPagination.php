<?php
namespace Appointment\Pagination;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Doctrine\ORM\EntityManager;

/**
 * Class AppointmentPagination
 *
 * @package Appointment\Pagination
 */
class AppointmentPagination
{
    const ITEMS_PER_PAGE    = 10;
    private $paginator;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $repository = $entityManager->getRepository('Appointment\Entity\Appointment');

        $ormPagination = new ORMPaginator($repository->createQueryBuilder('appointment'));
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


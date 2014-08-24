<?php
namespace Arbitration\Mapper;

use Doctrine\ORM\Query\Expr\Join;
use Nakade\Pagination\ItemPagination;
use Support\Mapper\DefaultMapper;

/**
 * Class RefereeMapper
 *
 * @package Arbitration\Mapper
 */
class RefereeMapper extends DefaultMapper
{

    /**
     * @param int|null $offset
     *
     * @return array
     */
    public function getActiveRefereesByPages($offset=null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('User')
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Support\Entity\Referee', 'r', Join::WITH, 'r.user = u')
            ->where('r.user = u')
            ->andWhere('r.isActive = true')
            ->andWhere('u.active = true');

        if (isset($offset)) {
            $qb->setFirstResult($offset)
                ->setMaxResults(ItemPagination::ITEMS_PER_PAGE);
        }

        $this->addRefereeRoles($qb);
        return $qb->getQuery()->getResult();

    }
}


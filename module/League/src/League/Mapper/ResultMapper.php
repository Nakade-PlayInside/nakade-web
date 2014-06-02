<?php
namespace League\Mapper;

use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\Query\Expr\Join;
use \Doctrine\ORM\Query;


/**
 * Class ResultMapper
 *
 * @package League\Mapper
 */
class ResultMapper  extends AbstractMapper
{

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getOpenResultsBySeason($seasonId)
    {
        $now = new \DateTime();
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'l.season = s')
            ->innerJoin('l.season', 'Season')
            ->where('m.result IS NULL')
            ->andWhere('Season.id = :seasonId')
            ->andWhere('m.date < :now')
            ->setParameter('seasonId', $seasonId)
            ->setParameter('now', $now)
            ->orderBy('m.date', 'ASC')
            ->addOrderBy('m.id', 'ASC');

        return $qb->getQuery()->getResult();

    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getAllOpenResultsBySeason($seasonId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'l.season = s')
            ->innerJoin('l.season', 'Season')
            ->where('m.result IS NULL')
            ->andWhere('Season.id = :seasonId')
            ->setParameter('seasonId', $seasonId)
            ->orderBy('m.date', 'ASC')
            ->addOrderBy('m.id', 'ASC');

        return $qb->getQuery()->getResult();

    }

    /**
     * @param int $seasonId
     * @param int $userId
     *
     * @return array
     */
    public function getResultsByUser($seasonId, $userId)
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'l.season = s')
            ->innerJoin('l.season', 'Season')
            ->innerJoin('m.black', 'Black')
            ->innerJoin('m.white', 'White')
            ->where('Season.id = :seasonId')
            ->andWhere('Black.id = :uid OR White.id = :uid')
            ->setParameter('seasonId', $seasonId)
            ->setParameter('uid', $userId)
            ->orderBy('m.date', 'ASC')
            ->addOrderBy('m.id', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $seasonId
     * @param int $userId
     *
     * @return array
     */
    public function getOpenResultsByUser($seasonId, $userId)
    {

        $now = new \DateTime();
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'l.season = s')
            ->innerJoin('l.season', 'Season')
            ->innerJoin('m.black', 'Black')
            ->innerJoin('m.white', 'White')
            ->where('m.result IS NULL')
            ->andWhere('Season.id = :seasonId')
            ->andWhere('m.date < :now')
            ->andWhere('Black.id = :uid OR White.id = :uid')
            ->setParameter('seasonId', $seasonId)
            ->setParameter('now', $now)
            ->setParameter('uid', $userId)
            ->orderBy('m.date', 'ASC')
            ->addOrderBy('m.id', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $matchId
     *
     * @return object
     */
    public function getMatchById($matchId)
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\Match')
            ->find($matchId);

    }
}


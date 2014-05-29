<?php
namespace Season\Mapper;

use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\Query\Expr\Join;
use \Doctrine\ORM\Query;
/**
 * Description of PlayerMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class ParticipantMapper extends AbstractMapper
{


    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getParticipantsBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('p')
            ->from('Season\Entity\Participant', 'p')
            ->innerJoin('p.season', 'MySeason')
            ->where('MySeason.id = :seasonId')
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getInvitedUsersBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('u')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Season\Entity\Participant', 'p', Join::WITH, 'p.user = u')
            ->innerJoin('p.season', 'Season')
            ->where('Season.id = :seasonId')
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getAcceptingUsersBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('u')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Season\Entity\Participant', 'p', Join::WITH, 'p.user = u')
            ->innerJoin('p.season', 'Season')
            ->where('Season.id = :seasonId')
            ->andWhere('p.hasAccepted = 1')
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    private function getInvitedPlayerIdsBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('u.id')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Season\Entity\Participant', 'p', Join::WITH, 'u = p.user')
            ->innerJoin('p.season', 'Season')
            ->where('Season.id = :seasonId')
            ->setParameter('seasonId', $seasonId);

        $result = $qb->getQuery()->getResult();

        //quicker than array_map
        $ids = array();
        foreach ($result as $item) {
            $ids[] = $item['id'];
        }

        return $ids;
    }


    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getAvailablePlayersBySeason($seasonId)
    {
        $notIn = $this->getInvitedPlayerIdsBySeason($seasonId);
        //mandatory array is never empty
        if (empty($notIn)) {
            $notIn[]=0;
        }

        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('u')
            ->from('User\Entity\User', 'u')
            ->where($qb->expr()->notIn('u.id', $notIn))
            ->andWhere('u.active = true')
            ->andWhere('u.verified = true')
            ->orderBy('u.firstname', 'ASC')
            ->addOrderBy('u.lastname', 'ASC');

        return $qb->getQuery()->getResult();
    }
}

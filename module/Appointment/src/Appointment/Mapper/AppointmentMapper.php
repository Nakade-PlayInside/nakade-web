<?php
namespace Appointment\Mapper;

use Doctrine\ORM\Query;
use \User\Entity\User;
use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\EntityManager;
use Season\Entity\Match;

/**
 * Requesting database using doctrine
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class AppointmentMapper extends AbstractMapper
{

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
            $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     *
     * @return \Appointment\Entity\Appointment
     */
    public function getAppointmentById($id)
    {
       return $this->getEntityManager()
                   ->getRepository('Appointment\Entity\Appointment')
                   ->find($id);
    }

    /**
     * @param Match $match
     *
     * @return array
     */
    public function getAppointmentByMatch(Match $match)
    {
        return $this->getEntityManager()
            ->getRepository('Appointment\Entity\Appointment')
            ->findBy(array('match' => $match));
    }

    /**
     * @param \DateTime $time
     *
     * @return array
     */
    public function getOverdueAppointments($time)
    {
        $modifyStr = sprintf('-%d hour', $time);
        $now = new \DateTime();
        $overdue = $now->modify($modifyStr);

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Appointment')
            ->select('a')
            ->from('Appointment\Entity\Appointment', 'a')
            ->where('a.isConfirmed = 0')
            ->andWhere('a.isRejected = 0')
            ->andWhere('a.submitDate < :overdue')
            ->setParameter('overdue', $overdue);

        return $qb->getQuery()->getResult();

    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getOpenConfirmsByUser(User $user)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Appointment')
            ->select('a')
            ->from('Appointment\Entity\Appointment', 'a')
            ->join('a.responder', 'User')
            ->join('a.match', 'Match')
            ->where('a.isConfirmed = 0')
            ->andWhere('a.isRejected = 0')
            ->andWhere('User.id = :uid')
            ->andWhere('Match.result IS NULL')
            ->setParameter('uid', $user->getId());

        return $qb->getQuery()->getResult();

    }

    /**
     * @return array
     *
     * @todo: filter by league and season
     */
    public function getRejectedAppointments()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Appointment')
            ->select('a')
            ->from('Appointment\Entity\Appointment', 'a')
            ->Where('a.isRejected = 1');

        return $qb->getQuery()->getResult();

    }

    /**
     * Needed for getMatchesOpenForAppointmentByUser
     *
     * @param int $userId
     *
     * @return array
     */
    public function getMatchIdsByUser($userId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Appointment');
        $result = $qb
            ->select('Match.id')
            ->from('Appointment\Entity\Appointment', 'a')
            ->join('a.match', 'Match')
            ->join('a.responder', 'Responder')
            ->join('a.submitter', 'Submitter')
            ->where('Responder.id = :uid OR Submitter.id = :uid')
            ->setParameter('uid', $userId)
            ->getQuery()
            ->getResult();

        //quicker than array_map
        $ids = array();
        foreach ($result as $item) {
            $ids[] = $item['id'];
        }

        return $ids;
    }

    /**
     * @param User  $userId
     * @param int   $timeLimit
     *
     * @return array
     */
    public function getMatchesOpenForAppointmentByUser($userId, $timeLimit=72)
    {

        $shiftedMatches = $this->getMatchIdsByUser($userId);

        //mandatory array is never empty
        if (empty($shiftedMatches)) {
            $shiftedMatches[]=0;
        }

        $now = new \DateTime();
        $now->modify('+'.$timeLimit.' hour');

        $qb = $this->getEntityManager()->createQueryBuilder('Match');
        $qb->select('m')
            ->from('Season\Entity\Match', 'm')
            ->join('m.black', 'Black')
            ->join('m.white', 'White')
            ->where($qb->expr()->notIn('m.id', $shiftedMatches))
            ->andWhere('m.result is Null')
            ->andWhere('Black.id = :uid OR White.id = :uid')
            ->andWhere('m.date > :deadline')
            ->setParameter('uid', $userId)
            ->setParameter('deadline', $now)
            ->orderBy('m.date ', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * get the match by id
     *
     * @param int $id
     *
     * @return \Season\Entity\Match
     */
    public function getMatchById($id)
    {

        return $this->getEntityManager()
            ->getRepository('Season\Entity\Match')
            ->find($id);

    }

}

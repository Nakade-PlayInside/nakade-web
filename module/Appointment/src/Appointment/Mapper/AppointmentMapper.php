<?php
namespace Appointment\Mapper;

use Appointment\Pagination\AppointmentPagination;
use Doctrine\ORM\Query;
use \User\Entity\User;
use Nakade\Abstracts\AbstractMapper;
use Season\Entity\Match;

/**
 * Class AppointmentMapper
 *
 * @package Appointment\Mapper
 */
class AppointmentMapper extends AbstractMapper
{

    /**
     * @param int $offset
     *
     * @return array
     */
    public function getAppointmentsByPages($offset)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Appointment')
            ->select('a')
            ->from('Appointment\Entity\Appointment', 'a')
            ->setFirstResult($offset)
            ->setMaxResults(AppointmentPagination::ITEMS_PER_PAGE)
            ->addOrderBy('a.submitDate', 'DESC');

        return $qb->getQuery()->getResult();
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
     * @param int $hour
     *
     * @return array
     */
    public function getOverdueAppointments($hour)
    {
        $modifyStr = sprintf('-%d hour', $hour);
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

        return $this->getIdArray($result);
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

    /**
     * @return array
     */
    public function getExpiredAppointments()
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Appointment')
            ->select('a')
            ->from('Appointment\Entity\Appointment', 'a')
            ->innerJoin('a.match', 'Match')
            ->Where('Match.date < :today')
            ->andWhere('Match.result IS NOT NULL')
            ->setParameter('today', new \DateTime());

        return $qb->getQuery()->getResult();
    }

    /**
     * @param array $result
     *
     * @return array
     */
    private function getIdArray(array $result) {

        $idArray = array();
        foreach ($result as $item) {
            $idArray[] = $item['id'];
        }
        if (empty($idArray)) {
            $idArray[]=0;
        }

        return array_unique($idArray);
    }

}

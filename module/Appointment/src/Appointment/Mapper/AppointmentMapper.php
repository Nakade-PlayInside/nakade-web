<?php
namespace Appointment\Mapper;

use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\EntityManager;
use League\Entity\Match;

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
     * @param \DateTime $overdue
     *
     * @return array
     */
    public function getOverdueAppointments($overdue)
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Appointment')
            ->select('a')
            ->from('Appointment\Entity\Appointment', 'a')
            ->where('a.isConfirmed = 0')
            ->andWhere('a.isRejected = 0')
            ->andWhere('a.newDate > :overdue')
            ->setParameter('overdue', $overdue);

        return $qb->getQuery()->getResult();

    }

    /**
     * @param string $confirmString
     *
     * @return array
     */
    public function getAppointmentByConfirmString($confirmString)
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Appointment')
            ->select('a')
            ->from('Appointment\Entity\Appointment', 'a')
            ->where('a.isConfirmed = 0')
            ->andWhere('a.isRejected = 0')
            ->andWhere('a.confirmString LIKE :confirmString')
            ->setParameter('confirmString', $confirmString);

        return $qb->getQuery()->getResult();

    }

}

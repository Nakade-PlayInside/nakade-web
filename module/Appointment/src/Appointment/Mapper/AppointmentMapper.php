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
     * @return array
     */
    public function getOverdueAppointments()
    {

        $overdue = new \DateTime();
        $overdue->modify('-72h');

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

}

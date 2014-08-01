<?php
namespace User\Mapper;

use Nakade\Abstracts\AbstractMapper;
use \User\Entity\User;

/**
 * Requesting database using doctrine
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class UserMapper extends AbstractMapper
{

    /**
     * Get all registered User
     *
     * @return array User\Entity\User
     */
    public function getAllUser()
    {
        return $this->getEntityManager()
            ->getRepository('User\Entity\User')
            ->findAll();
    }

    /**
     * @param int $offset
     * @param int $max
     *
     * @return array
     */
    public function getUserByPages($offset, $max=10)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('User')
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->setFirstResult($offset)
            ->setMaxResults($max);

        return $qb->getQuery()->getResult();
    }

    /**
     * get User by its email and verify string
     *
     * @param string $email
     * @param string $verifyString
     *
     * @return User
     */
    public function getUserByEmailAndVerifyString($email, $verifyString)
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('User')
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->where('u.email = :email')
            ->andWhere('u.verifyString = :verifyString')
            ->setParameter('email', $email)
            ->setParameter('verifyString', $verifyString);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param int $uid
     *
     * @return User
     */
    public function getUserById($uid)
    {
        return $this->getEntityManager()
            ->getRepository('User\Entity\User')
            ->find($uid);
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function getUserByEmail($email)
    {

        return $this->getEntityManager()
            ->getRepository('User\Entity\User')
            ->findOneBy(array('email' => $email));
    }

    /**
     * @param string $code
     *
     * @return \User\Entity\Coupon
     */
    public function getCouponByCode($code)
    {

        return $this->getEntityManager()
            ->getRepository('User\Entity\Coupon')
            ->findOneBy(array('code' => $code));
    }
}

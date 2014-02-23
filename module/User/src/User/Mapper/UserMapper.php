<?php
namespace User\Mapper;

use Nakade\Abstracts\AbstractMapper;

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
     * @return array \User\Entity\User
     */
    public function getAllUser()  
    {
       return $this->getEntityManager()
                   ->getRepository('User\Entity\User')
                   ->findAll();
    }

    /**
     * Get User by its email and verifystring if due date
     * is not expired 
     *
     * @param string $email
     * @param string $verifyString
     *
     * @return \User\Entity\User
     */
    public function getActivateUser($email, $verifyString)
    {

      $dql = "SELECT u as user FROM User\Entity\User u
              WHERE u.email=:email AND u.verifyString=:code 
              AND u.due > :today";

      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->setParameter('email', $email)
                     ->setParameter('code', $verifyString)
                     ->setParameter('today', new \DateTime())
                     ->getOneOrNullResult();

      return $result['user'];

    }

    /**
     * @param int $uid
     *
     * @return \User\Entity\User
     */
    public function getUserById($uid)
    {
       $dql = "SELECT u as user 
               FROM User\Entity\User u
               WHERE u.id=:uid";

      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->setParameter('uid', $uid)
                     ->getOneOrNullResult();

      return $result['user'];

    }

    /**
     * Get User by its email
     *
     * @param string $email
     * 
     * @return \User\Entity\User $user|null
     */
    public function getUserByEmail($email)
    {
       $dql = "SELECT u as user 
               FROM User\Entity\User u
               WHERE u.email=:email";

      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->setParameter('email', $email)
                     ->getOneOrNullResult();

      return $result['user'];

    }
}

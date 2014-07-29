<?php
namespace User\Form\Hydrator;

use User\Entity\User;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;

/**
 * Class CouponHydrator
 *
 * @package User\Form\Hydrator
 */
class CouponHydrator implements HydratorInterface
{
    private $authenticationService;
    private $entityManager;

    /**
     * @param EntityManager         $em
     * @param AuthenticationService $auth
     */
    public function __construct(EntityManager $em, AuthenticationService $auth)
    {
        $this->entityManager = $em;
        $this->authenticationService = $auth;
    }

    /**
     * @param \User\Entity\Coupon $object
     *
     * @return array
     */
    public function extract($object)
    {
        return array(
            'email'=> $object->getEmail(),
            'code' => $object->getCode(),
        );
    }

    /**
     * @param array             $data
     * @param \User\Entity\Coupon $object
     *
     * @return \User\Entity\Coupon
     */
    public function hydrate(array $data, $object)
    {

        if (isset($data['email'])) {
            $object->setEmail($data['email']);
        }
        if (isset($data['message']) && !empty($data['message'])) {
            $object->setMessage($data['message']);
        }

        // add new coupon: created, expiry, code, user
        if (is_null($object->getId())) {

            $now  = new \DateTime();
            $object->setCreationDate($now);

            $date = clone $now;
            $expire = $date->modify('+ 6 week');
            $object->setExpiryDate($expire);

            $code = md5(uniqid(rand(), true));
            $object->setCode($code);

            $creator = $this->getCreator();
            $object->setCreatedBy($creator);

        }

        return $object;
    }

    /**
     * @return AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /**
     * @return User
     */
    private function getCreator()
    {
        $authService = $this->getAuthenticationService();
        if (!$authService->hasIdentity()) {
            return null;
        }

        $userId = $authService->getIdentity()->getId();
        return $this->getEntityManager()->getReference('User\Entity\User', intval($userId));
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}

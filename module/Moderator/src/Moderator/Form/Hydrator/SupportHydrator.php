<?php
namespace Moderator\Form\Hydrator;

use Moderator\Form\SupportInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;

/**
 * Class SupportHydrator
 *
 * @package Moderator\Form\Hydrator
 */
class SupportHydrator implements HydratorInterface, SupportInterface
{
    private $entityManager;
    private $authenticationService;

    /**
     * @param EntityManager $em
     * @param AuthenticationService $auth
     */
    public function __construct(EntityManager $em, AuthenticationService $auth)
    {
        $this->entityManager = $em;
        $this->authenticationService = $auth;
    }

    /**
     * @param \Moderator\Entity\SupportRequest $object
     *
     * @return array
     */
    public function extract($object)
    {
        $association = 1; //default
        if (null!==$object->getAssociation()) {
            $association = $object->getAssociation();

        }
        return array(
            self::ASSOCIATION => $association,
        );
    }

    /**
     * @param array  $data
     * @param \Moderator\Entity\SupportRequest $object
     *
     * @return \Moderator\Entity\SupportRequest
     */
    public function hydrate(array $data, $object)
    {
        if (is_null($object->getId())) {

            $object->setRequestDate(new \DateTime());
            $requestedBy = $this->getCreator();
            $object->setRequestedBy($requestedBy);

            if (isset($data[self::ASSOCIATION])) {
                $association = $this->getAssociationById($data[self::ASSOCIATION]);
                $object->setAssociation($association);
            }
        }

        if (isset($data[self::SUBJECT])) {
            $object->setSubject($data[self::ASSOCIATION]);
        }

        if (isset($data[self::MESSAGE])) {
            $object->setMessage($data[self::MESSAGE]);
        }


        return $object;
    }


    /**
     * @return AuthenticationService
     */
    private function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /**
     * @param int $associationId
     *
     * @return \Season\Entity\Association
     */
    private function getAssociationById($associationId)
    {
        return $this->getEntityManager()->getReference('Season\Entity\Association', $associationId);
    }

    /**
     * @param int $userId
     *
     * @return \User\Entity\User
     */
    private function getUserById($userId)
    {
        return $this->getEntityManager()->getReference('User\Entity\User', intval($userId));
    }

    /**
     * @return \User\Entity\User
     */
    private function getCreator()
    {
        $authService = $this->getAuthenticationService();
        if (!$authService->hasIdentity()) {
            return null;
        }

        $userId = $authService->getIdentity()->getId();
        return $this->getUserById($userId);
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->entityManager;
    }

}

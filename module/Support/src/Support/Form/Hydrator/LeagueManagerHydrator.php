<?php
namespace Support\Form\Hydrator;

use Support\Form\ManagerInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;

/**
 * Class LeagueManagerHydrator
 *
 * @package Support\Form\Hydrator
 */
class LeagueManagerHydrator implements HydratorInterface, ManagerInterface
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
     * @param \Support\Entity\LeagueManager $object
     *
     * @return array
     */
    public function extract($object)
    {
        $association = null;
        if (null!==$object->getAssociation()) {
            $association = $object->getAssociation();

        }

        $manager = null;
        if (null!==$object->getManager()) {
            $manager = $object->getManager();
        }

        return array(
            self::ASSOCIATION => $association,
            self::MANAGER => $manager
        );
    }

    /**
     * @param array  $data
     * @param \Support\Entity\LeagueManager $object
     *
     * @return \Support\Entity\LeagueManager
     */
    public function hydrate(array $data, $object)
    {
        if (is_null($object->getId())) {
            $nominator = $this->getCreator();
            $object->setNominatedBy($nominator);
        }

        if (isset($data[self::ASSOCIATION])) {
            $association = $this->getAssociationById($data[self::ASSOCIATION]);
            $object->setAssociation($association);
        }

        if (isset($data[self::MANAGER])) {
            $manager = $this->getUserById($data[self::MANAGER]);
            $object->setManager($manager);
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

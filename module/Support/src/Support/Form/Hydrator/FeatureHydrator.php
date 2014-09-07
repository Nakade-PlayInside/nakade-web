<?php
namespace Support\Form\Hydrator;

use Support\Entity\StageInterface;
use Support\Form\FeatureInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class FeatureHydrator
 *
 * @package Support\Form\Hydrator
 */
class FeatureHydrator implements HydratorInterface, StageInterface, FeatureInterface
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
     * @param \Support\Entity\Feature $object
     *
     * @return array
     */
    public function extract($object)
    {
        return array(
            self::DESCRIPTION => $object->getDescription(),
            self::TYPE => $object->getType(),

        );
    }

    /**
     * @param array  $data
     * @param \Support\Entity\Feature $object
     *
     * @return \Support\Entity\Feature
     */
    public function hydrate(array $data, $object)
    {
        if (isset($data[self::DESCRIPTION])) {

            $object->setDescription($data[self::DESCRIPTION]);
        }

        if (isset($data[self::TYPE])) {
            $object->setType($data[self::TYPE]);
        }

        if (is_null($object->getId())) {

            $author = $this->getCreator();
            $object->setCreator($author);
            $stage = $this->getStageById(self::STAGE_NEW);
            $object->setStage($stage);
            $this->getEntityManager()->persist($object);
            $this->getEntityManager()->flush();
        }

        return $object;
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
     * @param int $stageId
     *
     * @return \Support\Entity\SupportStage
     */
    private function getStageById($stageId)
    {
        return $this->getEntityManager()->getReference('Support\Entity\SupportStage', intval($stageId));
    }

    /**
     * @return AuthenticationService
     */
    private function getAuthenticationService()
    {
        return $this->authenticationService;
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

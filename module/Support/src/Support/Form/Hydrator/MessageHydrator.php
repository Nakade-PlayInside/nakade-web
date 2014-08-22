<?php
namespace Support\Form\Hydrator;

use Support\Entity\StageInterface;
use Support\Form\SupportInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;

/**
 * Class MessageHydrator
 *
 * @package Support\Form\Hydrator
 */
class MessageHydrator implements HydratorInterface, SupportInterface, StageInterface
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
     * @param \Support\Entity\SupportMessage $object
     *
     * @return array
     */
    public function extract($object)
    {
        return array(
            self::SUBJECT => $object->getRequest()->getSubject()->getName(),
            self::STAGE => $object->getRequest()->getStage()->getId(),
        );
    }

    /**
     * @param array  $data
     * @param \Support\Entity\SupportMessage $object
     *
     * @return \Support\Entity\SupportMessage
     */
    public function hydrate(array $data, $object)
    {

        if (isset($data[self::MESSAGE])) {

            $author = $this->getCreator();
            $object->setAuthor($author);
            $object->setMessage($data[self::MESSAGE]);
        }

        if (isset($data[self::STAGE])) {

            $ticket = $object->getRequest();
            $stage = $this->getStageById($data[self::STAGE]);
            $ticket->setStage($stage);
            $this->getEntityManager()->persist($object);
            $this->getEntityManager()->flush();
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

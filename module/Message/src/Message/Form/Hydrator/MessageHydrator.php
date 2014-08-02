<?php
namespace Message\Form\Hydrator;

use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;

class MessageHydrator implements HydratorInterface
{
    private $authenticationService;
    private $entityManager;

    public function __construct(EntityManager $em, AuthenticationService $auth)
    {
        $this->entityManager = $em;
        $this->authenticationService = $auth;
    }

    /**
     * @param \Message\Entity\Message $object
     *
     * @return array
     */
    public function extract($object)
    {
        $receiverId = null;
        if (null!==$object->getReceiver()) {
            $receiverId = $object->getReceiver()->getId();
        }

        return array(
            'receiver'=>  $receiverId,
            'subject' =>  $object->getSubject(),
            'message' =>  $object->getMessage(),
        );

    }

    /**
     * @param array             $data
     * @param \Message\Entity\Message $object
     *
     * @return \Message\Entity\Message
     */
    public function hydrate(array $data, $object)
    {
        if (isset($data['receiver'])) {
            $receiver = $this->getUserById($data['receiver']);
            $object->setReceiver($receiver);
        }

        if (isset($data['subject'])) {
            $object->setSubject($data['subject']);
        }
        if (isset($data['message'])) {
            $object->setMessage($data['message']);
        }

        // add new user: created, due Date, verifyString
        if (is_null($object->getId())) {
            $sender = $this->getCreator();
            $object->setSender($sender);
            $object->setSendDate(new \DateTime());
            $object->setNew(true);
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
        return $this->getEntityManager()->getReference('User\Entity\User', $userId);
    }


    /**
     * @return AuthenticationService
     */
    public function getAuthenticationService()
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
        return $this->getUserById(intval($userId));
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

}

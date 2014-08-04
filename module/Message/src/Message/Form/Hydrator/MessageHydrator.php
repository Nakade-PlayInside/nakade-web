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
        $receiver = null;
        if (null!==$object->getReceiver()) {
            $receiver = $object->getSender()->getShortName();
        }

        $subject = null;
        if (null!==$object->getSubject()) {
            $subject .= 'Re:' . $object->getSubject();
        }

        return array(
            'sendTo'=>  $receiver,
            'subject' =>  $subject,
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
        $object->setSendDate(new \DateTime());
        $object->setNew(true);

        if (isset($data['receiver'])) {
            if (is_numeric($data['receiver'])) {
                $receiver = $this->getUserById($data['receiver']);
                $object->setReceiver($receiver);
            } else {
                //todo: make real messages for moderators
                $object->setModerator($data['receiver']);
                $receiver = $this->getUserById(1);
                $object->setReceiver($receiver);
            }

        }

        if (isset($data['subject'])) {
            $object->setSubject($data['subject']);
        }
        if (isset($data['message'])) {
            $object->setMessage($data['message']);
        }

        // new message
        if (is_null($object->getId())) {
            $sender = $this->getCreator();
            $object->setSender($sender);
        } else {
            $sender = $object->getReceiver();
            $receiver  = $object->getSender();
            $object->setSender($sender);
            $object->setReceiver($receiver);
            if (is_null($object->getThreadId())) {
                $threadId = $object->getId();
                $object->setThreadId($threadId);
            }
            $object->setId(null);
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

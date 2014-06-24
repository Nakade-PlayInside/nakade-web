<?php
namespace Appointment\Form\Hydrator;

use Appointment\Entity\Appointment;
use User\Entity\User;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;

/**
 * Class AppointmentHydrator
 *
 * @package Appointment\Form\Hydrator
 */
class AppointmentHydrator implements HydratorInterface
{

    private $entityManager;
    private $authenticationService;

    /**
     * @param EntityManager         $em
     * @param AuthenticationService $authenticationService
     */
    public function __construct(EntityManager $em, AuthenticationService $authenticationService)
    {
        $this->entityManager = $em;
        $this->authenticationService = $authenticationService;

    }

    /**
     * @param Appointment $appointment
     *
     * @return array
     */
    public function extract($appointment)
    {
        $data = array();

        /* @var $match /Match/Entity/Match */
        $match = $appointment->getMatch();
        if (!is_null($match)) {
            $data['oldDate'] = $match->getDate()->format('d.m.Y H:i:s');

            if ($this->isActiveUser($match->getBlack())) {
                $data['submitterId'] = $match->getBlack()->getId();
                $data['responderId'] = $match->getWhite()->getId();
            } else {
                $data['submitterId'] = $match->getWhite()->getId();
                $data['responderId'] = $match->getBlack()->getId();
            }
        }

        return $data;
    }

    /**
     * @param array       $data
     * @param Appointment $appointment
     *
     * @return object
     */
    public function hydrate(array $data, $appointment)
    {
        /* @var $season \Season\Entity\Season */
        $appointment->setSubmitDate(new \DateTime());

        $confirmString = md5(uniqid(rand(), true));
        $appointment->setConfirmString($confirmString);

        if (isset($data['oldDate'])) {
            $oldDate = \DateTime::createFromFormat('d.m.Y H:i:s', $data['oldDate']);
            $appointment->setOldDate($oldDate);
        }

        if (isset($data['submitterId'])) {
            $submitter = $this->getUser($data['submitterId']);
            $appointment->setSubmitter($submitter);
        }

        if (isset($data['responderId'])) {
            $responder = $this->getUser($data['responderId']);
            $appointment->setResponder($responder);
        }

        if (isset($data['date']) &&  isset($data['time'])) {
            $strDateTime = sprintf('%s %s', $data['date'], $data['time']);
            $newDate = \DateTime::createFromFormat('Y-m-d H:i:s', $strDateTime);
            $appointment->setNewDate($newDate);
        }

        return $appointment;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    private function isActiveUser(User $user)
    {
        $userId = $this->getIdentity()->getId();
        return $userId == $user->getId();

    }

    /**
     * @param int $userId
     *
     * @return \User\Entity\User
     */

    private function getUser($userId)
    {
        return $this->getEntityManager()->getReference('User\Entity\User', $userId);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
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
    private function getIdentity()
    {
        $authService = $this->getAuthenticationService();
        if (!$authService->hasIdentity()) {
            return null;
        }
        return $authService->getIdentity();
    }
}

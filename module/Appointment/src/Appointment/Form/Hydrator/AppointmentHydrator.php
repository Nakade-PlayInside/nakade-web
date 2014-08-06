<?php
namespace Appointment\Form\Hydrator;

use Appointment\Entity\Appointment;
use Appointment\Form\AppointmentInterface;
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
class AppointmentHydrator implements HydratorInterface, AppointmentInterface
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
     * @param Appointment $object
     * @return array
     * @throws \RuntimeException
     */
    public function extract($object)
    {
        $data = array();

        $match = $object->getMatch();
        if (is_null($match)) {
            throw new \RuntimeException(
                sprintf('Match data missing. You have to provide a match.')
            );
        }

        $data[self::FIELD_OLD_DATE] = $match->getDate()->format('d.m.Y H:i:s');

        return $data;
    }

    /**
     * @param array       $data
     * @param Appointment $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        if (isset($data[self::FIELD_OLD_DATE])) {
            $oldDate = \DateTime::createFromFormat('d.m.Y H:i:s', $data[self::FIELD_OLD_DATE]);
            $object->setOldDate($oldDate);
        }

        if (isset($data[self::FIELD_DATE]) &&  isset($data[self::FIELD_TIME])) {
            $strDateTime = sprintf('%s %s', $data[self::FIELD_DATE], $data[self::FIELD_TIME]);
            $newDate = \DateTime::createFromFormat('Y-m-d H:i:s', $strDateTime);
            $object->setNewDate($newDate);
        }

        //new appointment
        if (is_null($object->getId())) {
            $confirmString = md5(uniqid(rand(), true));
            $object->setConfirmString($confirmString);
            $object->setSubmitDate(new \DateTime());

            if (!is_null($object->getMatch())) {
                if ($this->isActiveUser($object->getMatch()->getBlack())) {
                    $submitter = $object->getMatch()->getBlack();
                    $responder = $object->getMatch()->getWhite();
                } else {
                    $responder = $object->getMatch()->getBlack();
                    $submitter = $object->getMatch()->getWhite();
                }

                $object->setSubmitter($submitter);
                $object->setResponder($responder);
            }

        }

        //new appointment by moderator
        if (isset($data[self::FIELD_MODERATOR_APPOINTMENT])) {

            $appointment = clone $object;
            $submitter = $this->getUser();
            $appointment->setSubmitter($submitter);
            $appointment->setSubmitDate(new \DateTime());

            $match = $appointment->getMatch();
            $date = $appointment->getNewDate();
            $sequence = $match->getSequence() + 1;

            $appointment->setIsConfirmed(true);
            $appointment->setIsRejected(false);
            $appointment->setRejectReason(null);

            $match->setDate($date);
            $match->setSequence($sequence);
            $appointment->setMatch($match);

            $object = $appointment;
        }

        if (isset($data[self::FIELD_REJECT_REASON])) {
            $object->setRejectReason($data[self::FIELD_REJECT_REASON]);
            $object->setIsRejected(true);
        }

        //confirming appointment
        if (isset($data[self::FIELD_CONFIRM_APPOINTMENT])) {

            $match = $object->getMatch();
            $date = $object->getNewDate();
            $sequence = $match->getSequence() + 1;

            $object->setIsConfirmed(true);

            $match->setDate($date);
            $match->setSequence($sequence);
            $object->setMatch($match);
        }

        return $object;
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
     * @return \User\Entity\User
     */
    private function getUser()
    {
        $userId = $this->getIdentity()->getId();
        return $this->getEntityManager()->getReference('User\Entity\User', intval($userId));
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

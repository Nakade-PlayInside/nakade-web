<?php
namespace Support\Form\Hydrator;

use Support\Entity\StageInterface;
use Support\Entity\SupportMessage;
use Support\Form\SupportInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class SupportHydrator
 *
 * @package Support\Form\Hydrator
 */
class SupportHydrator implements HydratorInterface, SupportInterface, StageInterface
{
    private $entityManager;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    /**
     * @param \Support\Entity\SupportRequest $object
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
     * @param \Support\Entity\SupportRequest $object
     *
     * @return \Support\Entity\SupportRequest
     */
    public function hydrate(array $data, $object)
    {
        if (is_null($object->getId())) {

            if (isset($data[self::ASSOCIATION])) {
                $association = $this->getAssociationById($data[self::ASSOCIATION]);
                $object->setAssociation($association);
            }
        }

        if (isset($data[self::SUBJECT])) {
            $subject = $this->getSubjectById($data[self::SUBJECT]);
            $object->setSubject($subject);
        }

        if (isset($data[self::MESSAGE])) {

            $stage = $this->getStageById(self::STAGE_NEW);
            $object->setStage($stage);

            $message = new SupportMessage($object, $object->getCreator());
            $message->setMessage($data[self::MESSAGE]);
            $object->addMessage($message);

            $this->getEntityManager()->persist($object);
            $this->getEntityManager()->flush();

        }


        return $object;
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
     * @param int $subjectId
     *
     * @return \Support\Entity\SupportSubject
     */
    private function getSubjectById($subjectId)
    {
        return $this->getEntityManager()->getReference('Support\Entity\SupportSubject', intval($subjectId));
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
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->entityManager;
    }

}

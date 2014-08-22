<?php
namespace Support\Form\Hydrator;

use Support\Form\ManagerInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class RefereeHydrator
 *
 * @package ModeSupportm\Hydrator
 */
class RefereeHydrator implements HydratorInterface, ManagerInterface
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
     * @param \Support\Entity\Referee $object
     *
     * @return array
     */
    public function extract($object)
    {

        $user = null;
        if (null!==$object->getUser()) {
            $user = $object->getUser();
        }

        return array(
            self::USER => $user
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
        if (isset($data[self::USER])) {
            $manager = $this->getUserById($data[self::USER]);
            $object->setManager($manager);
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
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->entityManager;
    }

}

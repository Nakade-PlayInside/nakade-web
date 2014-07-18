<?php
namespace User\Form\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class KgsHydrator
 *
 * @package User\Form
 */
class KgsHydrator implements HydratorInterface
{

    /**
     * @param \User\Entity\User $object
     *
     * @return array
     */
    public function extract($object)
    {
        $birthday = null;
        if (null!==$object->getBirthday()) {
            $birthday = $object->getBirthday()->format('Y-m-d');
        }

        return array(
                'birthday' => $birthday
        );
    }

    /**
     * @param array             $data
     * @param \User\Entity\User $object
     *
     * @return \User\Entity\User
     */
    public function hydrate(array $data, $object)
    {
        if (!empty($data['birthday'])) {
            $date = $data['birthday'];
            $birthday = new \DateTime($date);
            $object->setBirthday($birthday);
        }

        return $object;
    }

}

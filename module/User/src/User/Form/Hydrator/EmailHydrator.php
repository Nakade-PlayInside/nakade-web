<?php
namespace User\Form\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class EmailHydrator
 *
 * @package User\Form
 */
class EmailHydrator implements HydratorInterface
{

    /**
     * @param \User\Entity\User $object
     *
     * @return array
     */
    public function extract($object)
    {
        return array(
            'email' => $object->getEmail(),
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
        $email = $data['email'];
        $object->setEmail($email);

        return $object;
    }

}

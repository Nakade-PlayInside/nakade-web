<?php
namespace User\Form\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class PasswordHydrator
 *
 * @package User\Form\Hydrator
 */
class PasswordHydrator implements HydratorInterface
{

    /**
     * @param \User\Entity\User $object
     *
     * @return array
     */
    public function extract($object)
    {
        return array(
            'old' => $object->getPassword(),
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
        if (!empty($data['password'])) {
            $pwd = md5($data['password']);
            $date = new \DateTime();
            $object->setPassword($pwd);
            $object->setPwdChange($date);
        }

        return $object;
    }

}

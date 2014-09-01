<?php
namespace Authentication\Form\Hydrator;

use Authentication\Form\AuthInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class LoginHydrator
 *
 * @package Authentication\Form\Hydrator
 */
class LoginHydrator implements HydratorInterface, AuthInterface
{

    /**
     * @param \Authentication\Entity\Login $object
     *
     * @return array
     */
    public function extract($object)
    {
        return array(
            self::IDENTITY => $object->getIdentity(),
            self::REMEMBER => $object->isRememberMe()
        );
    }

    /**
     * @param array  $data
     * @param \Authentication\Entity\Login $object
     *
     * @return \Authentication\Entity\Login
     */
    public function hydrate(array $data, $object)
    {
        if (isset($data[self::IDENTITY])) {
            $object->setIdentity($data[self::IDENTITY]);
        }

        if (isset($data[self::PASSWORD])) {
            $object->setPassword($data[self::PASSWORD]);
        }

        if (isset($data[self::REMEMBER])) {
            $object->setRememberMe($data[self::REMEMBER]);
        }

        return $object;
    }

}

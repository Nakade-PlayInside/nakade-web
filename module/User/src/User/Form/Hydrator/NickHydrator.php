<?php
namespace User\Form\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class NickHydrator
 *
 * @package User\Form\Hydrator
 */
class NickHydrator implements HydratorInterface
{

    /**
     * @param \User\Entity\User $object
     *
     * @return array
     */
    public function extract($object)
    {
        return array(
                'nickname' => $object->getNickname(),
                'anonymous' => $object->isAnonym()
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
        $nick = null;
        if (!empty($data['nickname'])) {
            $nick = $data['nickname'];
        }
        $object->setNickname($nick);
        $object->setAnonym($data['anonymous']);

        return $object;
    }

}

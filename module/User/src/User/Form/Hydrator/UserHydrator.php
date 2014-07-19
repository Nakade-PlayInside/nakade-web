<?php
namespace User\Form\Hydrator;

use Permission\Entity\RoleInterface;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class UserHydrator
 *
 * @package User\Form\Hydrator
 */
class UserHydrator implements HydratorInterface, RoleInterface
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

        $language = 'no_NO';
        if (null!==$object->getLanguage()) {
            $language = $object->getLanguage();
        }

        $role = self::ROLE_USER;
        if (null!==$object->getRole()) {
            $role = $object->getRole();
        }

        $sex = 'm';
        if (null!==$object->getSex()) {
            $sex = $object->getSex();
        }

        return array(
            'nickname' => $object->getNickname(),
            'anonymous'=> $object->isAnonym(),
            'kgs'      => $object->getKgs(),
            'email'    => $object->getEmail(),
            'birthday' => $birthday,
            'language' => $language,
            'role'     => $role,
            'sex'      => $sex,
            'title'    => $object->getTitle(),
            'firstName'=> $object->getFirstname(),
            'lastName' => $object->getLastname(),
            'username' => $object->getUsername(),

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
        $object->setAnonym($data['anonym']);

        return $object;
    }

}

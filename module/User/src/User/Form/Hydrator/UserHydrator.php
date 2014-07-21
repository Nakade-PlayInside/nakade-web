<?php
namespace User\Form\Hydrator;

use Permission\Entity\RoleInterface;
use User\Form\Factory\LanguageInterface;
use User\Form\Factory\SexInterface;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class UserHydrator
 *
 * @package User\Form\Hydrator
 */
class UserHydrator implements HydratorInterface, RoleInterface, LanguageInterface, SexInterface
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

        $language = self::LANG_NO;
        if (null!==$object->getLanguage()) {
            $language = $object->getLanguage();

        }
        $role = self::ROLE_USER;
        if (null!==$object->getRole()) {
            $role = $object->getRole();
        }

        $sex = self::SEX_GENTLEMAN;
        if (null!==$object->getSex()) {
            $sex = $object->getSex();
        }

        return array(
            'anonymous'=> $object->isAnonym(),
            'nickname' => $object->getNickname(),
            'email'    => $object->getEmail(),
            'kgs'      => $object->getKgs(),
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
        if (isset($data['email'])) {
                $object->setEmail($data['email']);
        }
        if (isset($data['kgs'])) {
            if (empty($data['kgs'])) {
                $object->setKgs(null);
            } else {
                $object->setKgs($data['kgs']);
            }
        }
        if (isset($data['nickname'])) {
            if (empty($data['nickname'])) {
                $object->setNickname(null);
            } else {
                $object->setNickname($data['nickname']);
            }
        }
        if (isset($data['anonymous'])) {
                $nick = $object->getNickname();
                if (empty($nick)) {
                    $object->setAnonym(false);
                } else {
                    $object->setAnonym($data['anonymous']);
                }
        }
        if (isset($data['birthday'])) {
           if (empty($data['birthday'])) {
                $object->setBirthday(null);
           } else {
                $birthday = new \DateTime($data['birthday']);
                $object->setBirthday($birthday);
           }
        }
        if (isset($data['language'])) {
            if ($data['language'] == self::LANG_NO) {
                $object->setLanguage(null);
            } else {
                $object->setLanguage($data['language']);
            }
        }

        if (isset($data['password'])) {
            if (!empty($data['password'])) {
                $pwd = md5($data['password']);
                $object->setPassword($pwd);
                $date = new \DateTime();
                $object->setPwdChange($date);
            }
        }


        return $object;
    }

}

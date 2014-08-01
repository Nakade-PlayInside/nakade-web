<?php
namespace User\Form\Hydrator;

use Nakade\Generators\PasswordGenerator;
use Permission\Entity\RoleInterface;
use User\Entity\User;
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
    private $passwordGenerator;

    public function __construct(PasswordGenerator $passwordGenerator)
    {
        $this->passwordGenerator = $passwordGenerator;
    }

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
            'anonymous'=> $object->isAnonymous(),
            'nickname' => $object->getNickname(),
            'email'    => $object->getEmail(),
            'kgs'      => $object->getKgs(),
            'birthday' => $birthday,
            'language' => $language,
            'role'     => $role,
            'sex'      => $sex,
            'title'    => $object->getTitle(),
            'firstName'=> $object->getFirstName(),
            'lastName' => $object->getLastName(),
            'username' => $object->getUsername(),
            'code' => $object->getCouponCode(),
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
                    $object->setAnonymous(false);
                } else {
                    $object->setAnonymous($data['anonymous']);
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
            $this->setNewPassword($object, $data['password']);
        }

        if (isset($data['sex'])) {
            $object->setSex($data['sex']);
        }

        if (isset($data['title'])) {
            if (empty($data['title'])) {
                $object->setTitle(null);
            } else {
                $object->setTitle($data['title']);
            }
        }

        if (isset($data['firstName'])) {
                $object->setFirstName($data['firstName']);
        }

        if (isset($data['lastName'])) {
            $object->setLastName($data['lastName']);
        }

        if (isset($data['username'])) {
            $object->setUsername($data['username']);
        }

        if (isset($data['role'])) {
            $object->setRole($data['role']);
        }

        //for coupon stuff
        if (isset($data['code'])) {
            $object->setCouponCode($data['code']);
        }

        // add new user: created, due Date, verifyString
        if (is_null($object->getId())) {
           $this->setNewUserData($object);

        } else {
            $now  = new \DateTime();
            $object->setEdit($now);
        }

        //new email by profile editing
        if (isset($data['isNewEmail'])) {
            $this->setVerification($object);
        }

        return $object;
    }

    /**
     * @param User $user
     * @param string $pwdPlain
     */
    private function setNewPassword(User &$user, $pwdPlain)
    {
        $now = new \DateTime();
        $encryptedPwd = $this->getPasswordGenerator()->encryptPassword($pwdPlain);
        $user->setPassword($encryptedPwd);
        $user->setPwdChange($now);
    }

    /**
     * @param User &$user
     */
    private function setNewUserData(User &$user)
    {
        $now  = new \DateTime();
        $user->setCreated($now);
        $this->setVerification($user);
        $pwd = $this->getPasswordGenerator()->generatePassword();
        $pwdPlain = $this->getPasswordGenerator()->getPlainPassword();
        $user->setPasswordPlain($pwdPlain);
        $user->setPassword($pwd);
    }

    /**
     * @param User &$user
     */
    private function setVerification(User &$user)
    {
        $dueDate  = new \DateTime();
        $dueDate = $dueDate->modify('+ 72 hour');
        $user->setDue($dueDate);

        //random string
        $verifyString = md5(uniqid(rand(), true));
        $user->setVerifyString($verifyString);

        $user->setVerified(false);
    }

    /**
     * @return \Nakade\Generators\PasswordGenerator
     */
    public function getPasswordGenerator()
    {
        return $this->passwordGenerator;
    }



}

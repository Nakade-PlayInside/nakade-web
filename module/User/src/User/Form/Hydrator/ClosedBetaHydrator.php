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
 * Class ClosedBetaHydrator
 *
 * @package User\Form\Hydrator
 */
class ClosedBetaHydrator extends UserHydrator
{

    /**
     * @param User &$user
     */
    public function setVerification(User &$user)
    {
        $dueDate  = new \DateTime();
        $user->setDue($dueDate);

        //random string
        $verifyString = md5(uniqid(rand(), true));
        $user->setVerifyString($verifyString);

        $user->setVerified(true);
    }

}

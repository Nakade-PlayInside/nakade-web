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
 * Class UserHydratorInterface
 *
 * @package User\Form\Hydrator
 */
Interface UserHydratorInterface extends HydratorInterface
{
    /**
     * @param PasswordGenerator $passwordGenerator
     */
    public function __construct(PasswordGenerator $passwordGenerator);

    /**
     * @param User &$user
     */
    public function setVerification(User &$user);
    /**
     * @return \Nakade\Generators\PasswordGenerator
     */
    public function getPasswordGenerator();

}

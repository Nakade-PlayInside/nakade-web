<?php
namespace Appointment\View\Helper;

use Zend\View\Helper\AbstractHelper;
use User\Entity\User;

/**
 * Class UserInfo
 *
 * @package Appointment\View\Helper
 */
class UserInfo extends AbstractHelper
{

    /**
     * @param User $user
     *
     * @return string
     */
    public function __invoke(User $user)
    {
        $info = "Id: " . $user->getId() . PHP_EOL .
            $this->getView()->translate('Name') . ": " . $user->getName() . PHP_EOL .
            $this->getView()->translate('Email') . ": " . $user->getEmail();

        return $info;
    }
}
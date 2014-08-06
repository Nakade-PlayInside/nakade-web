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
            $this->translate('Name') . ": " . $user->getName() . PHP_EOL .
            $this->translate('Email') . ": " . $user->getEmail();

        return $info;
    }

    /**
     * @param string $message
     *
     * @return string
     */
    private function translate($message)
    {
        $translate = $this->getView()->plugin('translate');
        return $translate($message);
    }
}
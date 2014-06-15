<?php
namespace Season\View\Helper;

use User\Entity\User;
use Zend\View\Helper\AbstractHelper;

/**
 * Class PlayerAccepted
 *
 * @package Season\View\Helper
 */
class PlayerAccepted extends AbstractHelper
{
    /**
     * @param User  $user
     * @param array $userList
     *
     * @return string
     */
    public function __invoke(User $user, array $userList)
    {
        $translate = $this->getView()->plugin('translate');

        $info=$user->getName();
        if (in_array($user, $userList)) {
            $info = sprintf('<span>%s</span><img alt="accepted" title="%s" src="/images/active.png">',
                $user->getName(),
                $translate("Player accepted invitation")
            );
        }
        return $info;
    }
}

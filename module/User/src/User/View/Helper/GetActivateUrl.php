<?php
namespace User\View\Helper;
use User\Entity\User;
use Zend\View\Helper\AbstractHelper;

/**
 * Class GetActivateUrl
 *
 * @package User\View\Helper
 */
class GetActivateUrl extends AbstractHelper
{

    /**
     * @param User $user
     *
     * @return string
     */
    public function __invoke(User $user)
    {
        $url = $this->getView()->plugin('url');
        $href = $url('user', array('action' => 'undelete', 'id' => $user->getId()));

        if ($user->isActive()) {
            $href = $url('user', array('action' => 'delete', 'id' => $user->getId()));
        }

        return $href;
    }

}

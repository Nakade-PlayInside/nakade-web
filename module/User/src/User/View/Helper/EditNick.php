<?php
namespace User\View\Helper;
use User\Entity\User;

/**
 * helper for editing the nickname
 */
class EditNick extends AbstractProfileEditHelper
{

    /**
     * @param User $profile
     *
     * @return mixed|string
     */
    public function __invoke(User $profile)
    {
        $this->_url = "profile/nick";

        $img = $this->getAnonymousImage();
        $nick  = $profile->getNickname();
        $value = $profile->isAnonym()? $nick . $img : $nick;

        return $this->getLink($value);

    }


}

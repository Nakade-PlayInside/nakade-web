<?php
namespace User\View\Helper;
use User\Entity\User;

/**
 * helper for editing the birthday
 */
class EditBirthday extends AbstractProfileEditHelper
{
    /**
     * @param User $profile
     *
     * @return mixed|string
     */
    public function __invoke(User $profile)
    {
        $this->_url = "profile/birthday";
        $value = $this->convertDate($profile->getBirthday());

        return $this->getLink($value);
    }


}

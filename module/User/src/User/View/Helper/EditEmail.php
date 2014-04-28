<?php
namespace User\View\Helper;
use User\Entity\User;

/**
 * helper for editing the email adress
 */
class EditEmail extends AbstractProfileEditHelper
{
    /**
     * @param User $profile
     *
     * @return mixed|string
     */
    public function __invoke(User $profile)
    {
        $this->_url = "profile/email";
        $value = $profile->getEmail();


        return $this->getLink($value);
    }


}

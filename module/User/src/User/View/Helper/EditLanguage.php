<?php
namespace User\View\Helper;
use User\Entity\User;

/**
 * Class EditLanguage
 *
 * @package User\View\Helper
 */
class EditLanguage extends AbstractProfileEditHelper
{
    /**
     * @param User $profile
     *
     * @return string
     */
    public function __invoke(User $profile)
    {
        $this->_url = "profile/language";
        $value = $profile->getLanguage();


        return $this->getLink($value);
    }


}

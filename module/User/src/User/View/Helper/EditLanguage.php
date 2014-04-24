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
        $type = $profile->getLanguage();
        $value = $this->getLanguage($type);

        return $this->getLink($value);
    }

    private function getLanguage($type)
    {
        $language = 'unknown';
        switch ($type) {
            case 'de_DE':
                $language = $this->translate('German');
                break;
            case 'en_US':
                $language = $this->translate('English');
                break;

            default:
                $language = $this->translate('auto detect');
        }

        return $language;
    }
}

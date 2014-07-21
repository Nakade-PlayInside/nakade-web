<?php
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;
/**
 * Class showLanguage
 *
 * @package User\View\Helper
 */
class showLanguage extends AbstractHelper
{
    /**
     * @param string $type
     *
     * @return string
     */
    public function __invoke($type)
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

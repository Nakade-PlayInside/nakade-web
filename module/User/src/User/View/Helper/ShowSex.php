<?php
namespace User\View\Helper;

use User\Form\Factory\SexInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class Salutation
 *
 * @package User\View\Helper
 */
class ShowSex extends AbstractHelper implements SexInterface
{
    /**
     * @param string $sex
     *
     * @return string
     */
    public function __invoke($sex)
    {
        $class = 'male';
        if ($sex == self::SEX_LADY) {
            $class = 'female';
        }

        return $class;
    }

}

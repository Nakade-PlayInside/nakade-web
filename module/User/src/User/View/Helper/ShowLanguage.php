<?php
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;
/**
 * Class showLanguage
 *
 * @package User\View\Helper
 */
class ShowLanguage extends AbstractHelper
{
    /**
     * @param string $type
     * @param int    $size
     *
     * @return string
     */
    public function __invoke($type, $size=16)
    {
        $class = "pwdInfo automatic";
        if (!is_null($type)) {
            $class = sprintf("lang-%s %s-%s", $size, $type, $size);
        }
        return $class;
    }

}

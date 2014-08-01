<?php
namespace User\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * Class ShowAnonymous
 *
 * @package User\View\Helper
 */
class ShowAnonymous extends AbstractHelper
{
    /**
     * @param bool $isAnonymous
     *
     * @return string
     */
    public function __invoke($isAnonymous)
    {
        $class = '';
        if ($isAnonymous) {
            $class = 'anonymous';
        }
        return $class;
    }
}

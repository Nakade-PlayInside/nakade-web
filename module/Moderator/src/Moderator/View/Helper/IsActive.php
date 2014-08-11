<?php
namespace Moderator\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
/**
 * Class IsActive
 *
 * @package Moderator\View\Helper
 */
class IsActive extends AbstractHelper
{
    /**
     * @param bool $isActive
     *
     * @return string
     */
    public function __invoke($isActive)
    {
        $class = 'arrows-16 cross-16';
        if ($isActive) {
            $class = 'arrows-16 active-16';
        }

        return $class;
    }
}

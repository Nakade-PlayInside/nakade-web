<?php
namespace User\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * Shows date in format if a date isset
 *
 * @package User\View\Helper
 */
class ShowDate extends AbstractHelper
{
    /**
     * @param \DateTime $date
     *
     * @return string
     */
    public function __invoke(\DateTime $date=null)
    {
        $info = '';
        if (!empty($date)) {
            $info = $date->format('d.m.Y');
        }
        return $info;

    }
}

<?php
namespace User\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * Class ShowDateTime
 *
 * @package User\View\Helper
 */
class ShowDateTime extends AbstractHelper
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
            $info = $date->format('d.m.y H:i');
        }
        return $info;

    }
}

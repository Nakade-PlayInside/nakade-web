<?php
namespace User\View\Helper;

use Nakade\Abstracts\AbstractViewHelper;

/**
 * Shows date in format if a date isset
 *
 * @package User\View\Helper
 */
class ShowDate extends AbstractViewHelper
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

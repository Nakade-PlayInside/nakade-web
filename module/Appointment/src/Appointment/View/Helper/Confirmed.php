<?php
namespace Appointment\View\Helper;

use Appointment\Entity\Appointment;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * Class Confirmed
 *
 * @package Appointment\View\Helper
 */
class Confirmed extends AbstractHelper
{
    /**
     * @param Appointment $object
     *
     * @return string
     */
    public function __invoke(Appointment $object)
    {
        $class = 'mail-16 time-16';
        if ($object->isConfirmed()) {
            $class = "arrows-16 active-16";
        }
        if ($object->isRejected()) {
            $class = "arrows-16 cross-16";
        }
        return $class;
    }
}

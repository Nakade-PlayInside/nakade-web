<?php
namespace Application\View\Helper;

/**
 * Class GetAppointmentAmount
 *
 * @package Appointment\View\Helper
 */
class GetAppointmentAmount extends DefaultViewHelper
{

    /**
     * @return string
     */
    public function __invoke()
    {
        return $this->getAmount();
    }

}

<?php
namespace Application\View\Helper;

/**
 * Class GetInvitedAmount
 *
 * @package Appointment\View\Helper
 */
class GetInvitedAmount extends DefaultViewHelper
{

    /**
     * @return string
     */
    public function __invoke()
    {
        return $this->getAmount();
    }

}

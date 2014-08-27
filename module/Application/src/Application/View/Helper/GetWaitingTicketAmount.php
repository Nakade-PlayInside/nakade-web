<?php
namespace Application\View\Helper;

/**
 * Class GetWaitingTicketAmount
 *
 * @package Appointment\View\Helper
 */
class GetWaitingTicketAmount extends DefaultViewHelper
{

    /**
     * @return string
     */
    public function __invoke()
    {
        return $this->getAmount();
    }

}

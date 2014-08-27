<?php
namespace Application\View\Helper;

/**
 * Class GetMessageAmount
 *
 * @package Appointment\View\Helper
 */
class GetMessageAmount extends DefaultViewHelper
{

    /**
     * @return string
     */
    public function __invoke()
    {
        return 20;
        return $this->getAmount();
    }

}

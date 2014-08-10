<?php
namespace User\View\Helper;

use User\Entity\Coupon;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * Class ShowCouponStage
 *
 * @package User\View\Helper
 */
class ShowCouponStage extends AbstractHelper
{
    /**
     * @param Coupon $coupon
     *
     * @return string
     */
    public function __invoke(Coupon $coupon)
    {
        $class = 'mail-16 mail-yellow-16';
        if ($coupon->isUsed()) {
            $class = 'arrows-16 active-16';
        } elseif ($coupon->isExpired()) {
            $class = 'mail-16 time-16';
        }

        return $class;
    }
}
